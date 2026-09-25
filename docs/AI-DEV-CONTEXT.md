# AI Development Context — Hireskills

This file is the quick-reference Cursor reads while coding. Full detail lives in PRODUCT-SPEC.md, TECHNICAL-SPEC.md, UI-UX-SPEC.md, and FEATURE-SPECS.md — read the relevant feature entry in FEATURE-SPECS.md before implementing anything. This file exists so you don't have to re-derive the basics every session.

---

## 1. Project Overview

Hireskills is a localized freelancer discovery platform for Abuyog, Leyte. Freelancers build profiles; Employers browse, search, filter, and contact them directly. No job postings, no applications, no in-app payments, no in-app messaging. A structured Hiring Request replaces a job-application flow. Guests can browse and view everything except a freelancer's contact details; login is required only to act (contact, request, review, report).

Full detail: PRODUCT-SPEC.md.

## 2. Tech Stack

| Layer         | Choice                                                                                                                           |
| ------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| Backend       | Laravel 13, PHP 8.3+                                                                                                             |
| Frontend      | Inertia.js v3 + React 19 + TypeScript                                                                                            |
| Root template | Blade — one shell (`app.blade.php`) only, never individual pages                                                                 |
| Styling       | Tailwind CSS v4, shadcn-style components (ships with the starter kit)                                                            |
| Auth          | Laravel Fortify (session-based, never JWT) — extend `App\Actions\Fortify\CreateNewUser`, don't rewrite registration from scratch |
| Routing       | Laravel Wayfinder — typed route/controller references on the React side                                                          |
| ORM           | Eloquent                                                                                                                         |
| Database      | SQLite locally (already set up and migrated) — MySQL or PostgreSQL only if/when deploying, decided later                         |
| Passwords     | Laravel's `Hash` facade (bcrypt)                                                                                                 |
| Email         | Laravel Mail via a free SMTP provider                                                                                            |
| File storage  | Laravel `Storage` facade, local disk driver                                                                                      |
| Testing       | Pest (unit, feature, browser/E2E)                                                                                                |
| Local dev     | Laravel Herd + `composer run dev`                                                                                                |
| AI tooling    | Laravel Boost — already configured for Cursor, provides stack-level guidelines/skills that complement (not replace) this file    |

Constraint behind every choice: instructor requires latest Laravel; solo/AI-assisted development, no paid services or software, web-only, mobile-first, English-only. Full detail: TECHNICAL-SPEC.md Section 1.

Scaffolded but out of scope: Two-Factor Authentication, Passkeys, Password Confirmation, Teams. These exist in the codebase because the starter kit includes them — do not build UI, routes, or nav entries for them unless explicitly asked.

## 3. Architecture Rules

- One codebase, one deployable unit. Next.js handles both UI and API — do not introduce a separate backend service.
- Layering is strict: Route Handler → Service Layer (`lib/`) → Prisma → Database. Business logic (state transitions, ranking, reputation math) lives in `lib/`, never inline in a route handler or a React component.
- Every protected route checks the session server-side before running any logic — never rely on the client hiding a button as the only protection.
- The Hiring Request state machine (PRODUCT-SPEC.md Section 8.1) is enforced only in the service layer. No endpoint mutates `HiringRequest.state` directly without going through the transition check.
- API responses shape their own data — a Guest's profile response omits contact fields at the API layer, not just in the UI.

Full detail: TECHNICAL-SPEC.md Sections 2, 4.

## 4. Folder Structure

```
app/
  Actions/
    Fortify/          — already scaffolded, extend for registration; don't replace
    HiringRequest/     — state machine transitions
    Reputation/        — BR-006 recalculation
    Ranking/           — FEATURE-018 logic
  Http/
    Controllers/       — grouped by resource: Auth/, Freelancer/, Admin/, plus top-level controllers
    Requests/          — one Form Request class per form (Laravel's equivalent of a shared Zod schema)
    Middleware/         — role checks, forced email-OTP-verification gate
  Models/
  Policies/            — per-record authorization (e.g. only a request's owner can accept it)
  Mail/
database/
  migrations/          — starter kit's own (users, cache, jobs, passkeys, 2FA) plus this project's additions
  seeders/              — fixed category/skill taxonomy
  database.sqlite        — local dev database
routes/
  web.php               — page and form routes, Inertia responses
  api.php               — the few routes that need a plain JSON response
resources/
  js/
    pages/
      public/, freelancer/, employer/, admin/   — mirrors the access levels below
    components/
      ui/, discovery/, profile/, requests/, reviews/, admin/
    layouts/
    types/
  css/
  views/
    app.blade.php        — the single Inertia root template
tests/
  Feature/, Unit/, Browser/    — Pest
```

Folder groups under `resources/js/pages/` map directly to the access levels in PRODUCT-SPEC.md Section 4 — public, freelancer, employer, admin. Components are organized by feature area, not by type, so a feature's related pieces stay in one folder. Full detail: TECHNICAL-SPEC.md Section 7.

## 5. Coding Conventions

- TypeScript strict mode. No `any` without a comment explaining why it's unavoidable.
- One Zod schema per resource in `lib/validation/`, imported by both the API route and the corresponding form — never write the same validation twice.
- Components: PascalCase filenames matching the component name. Route handlers: lowercase, matching the Next.js App Router convention.
- No business logic inside JSX or route handlers — call into `lib/` instead.
- Prefer Server Components by default; mark `"use client"` only where interactivity is actually needed (forms, modals, state).
- Every mutating endpoint returns the standard error shape from TECHNICAL-SPEC.md Section 5.10 — don't invent a new error format per route.
- Reuse existing components from `components/ui/` before creating a new one with similar behavior.

## 6. Important Business Rules

Do not deviate from these without an explicit instruction — they are the rules most likely to get quietly broken by a plausible-looking shortcut.

- **One role per account, forever.** No account ever holds both Freelancer and Employer. No "switch role" feature exists.
- **Email verification is forced and mandatory**, immediately after registration, for both roles. A Freelancer profile cannot publish, and an Employer cannot send a request or review, without it.
- **Skills are never free text.** Only approved `Skill` rows are selectable. "Other" creates a `pending` Skill row, usable by no one until Admin approves it.
- **Reputation fields are always system-derived.** `rating_avg`, `rating_count`, `completed_jobs_count`, `clients_count` are never accepted as direct input on any endpoint.
- **Hiring Request states only move along the defined path**: Submitted → Pending Response → (optional) Request More Information → Accepted/Declined → (if Accepted) Completed/Cancelled. Any other transition is a 409, not a silent no-op.
- **One review per completed request, ever.** Enforced by a unique DB constraint, not just application logic.
- **Guests see everything on a profile except contact details.** Contact/Hire and Send Hiring Request buttons are visible to guests but trigger a login prompt instead of the action.
- **No superlative ranking language.** Never render "Best," "Top," or similar in result copy — only neutral phrasing like "Recommended for your search."
- **Unpublished profiles are invisible everywhere.** Excluded at the query layer from feed, search, and filter results — not filtered client-side.

Full detail: PRODUCT-SPEC.md Section 8, FEATURE-SPECS.md per-feature Business Rules sections.

## 7. Security Rules

- Passwords: bcrypt only, never plaintext, never logged.
- Sessions: httpOnly, signed/encrypted cookies via iron-session. No sensitive data readable from client-side JS.
- Every mutating endpoint validates input server-side with Zod — client-side validation is a UX convenience, not a security boundary.
- All database access through Prisma — no raw string-concatenated SQL.
- File uploads: validate actual file type/content server-side, not just the extension; enforce size limits server-side.
- Contact details, and any field marked role-gated in FEATURE-SPECS.md, are omitted from the API response itself for unauthorized viewers — never sent to the client and hidden by CSS.
- `/admin/*` routes and `/api/admin/*` endpoints reject any non-Admin session immediately, before any other logic runs.
- No secrets in code. Environment variables only, documented in `.env.example` with placeholder values.

Full detail: TECHNICAL-SPEC.md Section 6.

## 8. Commands

Confirmed, matching the actual project setup:

```
composer install             — install PHP dependencies
npm install                  — install JS dependencies
composer run dev             — start Laravel server + queue listener + Vite together (primary dev command)
php artisan migrate          — run migrations (SQLite locally, already set up)
php artisan migrate:fresh --seed   — reset the database and reseed categories/skills
php artisan pail             — readable local log tailing
php artisan test             — run Pest tests
vendor/bin/pest              — same, direct Pest invocation
npm run build                — production frontend build
```

Laravel Herd serves the app automatically at its `*.test` domain once the project is linked — no separate `serve` command needed for normal local use.

## 9. Testing Rules

- Every feature in FEATURE-SPECS.md maps to at least one test covering its Acceptance Criteria.
- Business rules with a state machine or a derived value (Hiring Request transitions, reputation recalculation, publish-eligibility checks) get unit tests in `lib/`, not just end-to-end coverage — these are the rules most likely to silently regress.
- Authorization boundaries (guest vs. employer vs. freelancer vs. admin) get explicit tests per route — verify both the allowed and the denied case, not just the happy path.
- Run relevant tests after implementing a feature, before reporting it done. Do not mark a feature complete if its tests are failing or missing.

## 10. AI Coding Rules

1. Before implementing a feature, read its full entry in FEATURE-SPECS.md, plus the sections it references in PRODUCT-SPEC.md, TECHNICAL-SPEC.md, and UI-UX-SPEC.md.
2. Do not invent requirements, fields, or business rules not present in those documents. If something is genuinely ambiguous or missing, say so and propose a default rather than silently deciding.
3. Work on one feature from FEATURE-SPECS.md at a time, in the build order listed there, unless told otherwise.
4. Never modify a feature or file unrelated to the current task without flagging it first.
5. Before major implementation, briefly state the plan: which files you'll touch, which endpoints/components you'll add, and any assumptions.
6. Reuse existing components, schemas, and service functions before writing new ones — check `components/ui/`, `lib/validation/`, and `lib/` first.
7. Follow the folder structure and conventions in Sections 4–5 exactly. Don't introduce a new pattern (state management library, CSS approach, folder convention) without discussion.
8. Run the relevant tests after implementing. Fix failures before reporting the feature done.
9. Report which files changed and what remains — don't silently leave a feature partially done without saying so.
10. If a request conflicts with a Business Rule in Section 6 or a rule in the referenced feature spec, flag the conflict instead of quietly implementing the request as asked.
11. Laravel Boost is already installed and configured in this project, providing its own generic guidelines and skills (fortify-development, inertia-react-development, laravel-best-practices, tailwindcss-development, testing-best-practices, wayfinder-development, infer-conventions). Follow those for general Laravel/Inertia/Fortify/Tailwind/testing conventions — this file takes precedence only where it states a product-specific rule (Sections 6–7) that a generic guideline wouldn't know about.
12. Do not build UI, routes, or navigation for Two-Factor Authentication, Passkeys, Password Confirmation, or Teams — they're present in the scaffolded codebase but are out of scope for this product.
