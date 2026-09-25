# UI/UX Specification — Hireskills

Answers: How should the application look and behave?
Depends on: Product Specification (routes, roles), Technical Specification (Tailwind CSS, shadcn/ui primitives)
Status legend: Confirmed, Recommendation

---

## 1. Design Principles

- Mobile-first. Every layout is designed for a narrow viewport first, then expanded for desktop. This is a Confirmed constraint (NFR-002), not a style preference.
- Discovery is the core loop. The Home/Feed and Freelancer Profile pages get the most design attention — everything else supports getting an employer from "I need X" to "I contacted someone" as fast as possible.
- Plain language over icons-only. Local users span a wide range of digital literacy; every icon-only action (report, contact) carries a visible text label, not just an icon.
- Trust is shown, not told. Verification badges, ratings, and completed-job counts are visually prominent on every profile card and profile page — they are the product's credibility mechanism (G4).
- No dead ends. Every empty, loading, and error state gives the user a next action, never just a blank screen or a generic message.

## 2. Layout Principles

- Single-column layouts on mobile (< 768px); content areas cap at a readable max-width on desktop (Recommendation: 1024px for content pages, 1280px for the admin dashboard).
- Primary navigation is a bottom tab bar on mobile, a top horizontal bar on desktop (≥ 768px) — same destinations, different position.
- Cards (freelancer cards, request cards, notification items) use consistent internal padding and spacing across the app; one card component is reused everywhere rather than each page styling its own.
- Forms are single-column at all breakpoints — two-column forms increase mis-tap rates on mobile and this product is mobile-first by default.

## 3. Navigation

### 3.1 Guest
Top bar (desktop) / top bar with hamburger-free simple layout (mobile): Logo/Home, Search icon, Login button, Register button.

### 3.2 Freelancer
Bottom tab bar (mobile) / top bar (desktop): Dashboard, My Profile, Requests, Notifications (with unread badge count), Account.

### 3.3 Employer
Bottom tab bar (mobile) / top bar (desktop): Discover, Requests, Reviews Given, Notifications (with unread badge count), Account.

### 3.4 Admin
Left sidebar (desktop) / top bar with dropdown (mobile, since Admin is a lower-traffic, less mobile-critical surface): Dashboard, Users, Freelancers, Categories, Reports, Verifications.

### 3.5 Navigation Rules
- The active route is always visually distinguished (color + weight, not color alone, for accessibility).
- Unread notification count shows as a badge on the Notifications nav item; badge disappears at zero, never shows "0."
- Switching between nav sections never loses unsaved form input without a confirmation prompt ("You have unsaved changes — leave anyway?").

## 4. Responsive Behavior

| Breakpoint | Range | Behavior |
|---|---|---|
| Mobile | < 768px | Single column, bottom tab nav, cards stack vertically, filters open as a full-screen sheet |
| Tablet | 768–1023px | Two-column feed grid, top nav bar, filters open as a side panel |
| Desktop | ≥ 1024px | Three-column feed grid, top nav bar, filters as a persistent sidebar on Search/Category pages |

Images (profile photos, portfolio items) always use responsive sizing with a fixed aspect ratio placeholder to prevent layout shift while loading.

## 5. Design System

### 5.1 Tokens (Recommendation — starting palette, adjustable)

| Token | Value | Use |
|---|---|---|
| Primary | A single accent color (e.g. a warm blue or teal) | Primary buttons, active nav state, links |
| Success | Green | Verified badges, Accepted/Completed states, success toasts |
| Warning | Amber | Pending states, unread emphasis |
| Danger | Red | Declined/Cancelled states, destructive actions, error text |
| Neutral scale | Gray 50–900 | Backgrounds, borders, body text |
| Background | White / Gray 50 | Page background |
| Surface | White | Card backgrounds |

Defined once as Tailwind theme tokens (`tailwind.config`), never hardcoded per component, so a palette change is a one-file edit.

### 5.2 Typography
- One typeface family, system-font stack for performance (no external font loading dependency, keeps pages fast on mobile data connections).
- Scale (Recommendation): 12px caption, 14px body-small, 16px body (default), 18px subheading, 24px heading, 32px page title. Mobile uses the same scale — no separate desktop-only larger sizes, since layout width changes, not text size.

### 5.3 Spacing
- 4px base unit, using Tailwind's default spacing scale (4, 8, 12, 16, 24, 32, 48, 64px). No arbitrary pixel values in component styles.

### 5.4 Core Components

| Component | States | Notes |
|---|---|---|
| Button | default, hover, active, disabled, loading (spinner replaces label) | Primary (filled), Secondary (outline), Destructive (red), Ghost (text-only) variants |
| Input / Textarea | default, focused, error, disabled | Error state shows red border + inline message below the field |
| Select / Dropdown | default, open, disabled | Used for category/skill pickers, filters |
| Card | default, hover (desktop only) | Used for freelancer cards, request cards, review cards |
| Badge | verified (green), pending (amber), rejected/declined (red), neutral (gray) | Used for verification badges and request/report status |
| Modal | open, closing | Used for Contact, Report, Leave a Review |
| Toast | success, error, info | Auto-dismiss after ~4 seconds, dismissible manually |
| Rating stars | display (read-only), input (interactive, 1–5) | Same visual component, interactive only on the review form |

## 6. Pages

Each entry follows the route table in Product Spec Section 12. This section defines layout intent and state handling per page; it does not repeat route/access rules already specified there.

### 6.1 Home / Discovery Feed (`/`)
- Search bar prominent at top, filter entry point beside or below it.
- Grid/list of freelancer cards: photo, name, top 1–2 skills, municipality/barangay, availability badge, rating, verification badge.
- Infinite scroll or "Load more" (Recommendation: infinite scroll on mobile, paginated on desktop).
- Loading state: skeleton cards matching the final card shape, not a spinner.
- Empty state: "No freelancers found yet in your area" with a link to browse all categories.

### 6.2 Search Results (`/search`)
- Same card grid as Home, with the active query shown and an easy way to clear it.
- Empty state: "No matches for '[query]'" with a suggestion to broaden the search or browse categories, not a dead end.

### 6.3 Category Browse / Category Listing (`/categories`, `/categories/:slug`)
- Category browse: a grid of the seven fixed categories, each with an icon/label.
- Category listing: same card grid as Home, pre-filtered.

### 6.4 Freelancer Profile (`/freelancers/:id`)
- Header: photo, name, location, availability badge, verification badges, rating summary.
- Body sections in order: Introduction, Skills (grouped by category), Professional info (experience, certifications), Portfolio (image grid), Pricing, Reviews (list with rating distribution).
- Action bar: Contact/Hire and Send Hiring Request buttons, always visible (sticky on mobile at the bottom of the viewport). For a Guest, both buttons are visibly present but tapping either opens the login/register prompt instead of the action (FR-026a) — the buttons are not hidden, since hiding them would obscure that the action exists.
- Report action: a smaller, secondary-style link, not a prominent button — visible to logged-in users only.
- Loading state: skeleton layout matching the section order above.
- Error/not-found state: "This profile is no longer available" with a link back to Home, not a generic 404.

### 6.5 Registration (`/register`, `/register/freelancer`, `/register/employer`)
- Role selection: two large tappable cards, "I'm a Freelancer" / "I'm looking to hire," each with a one-line description of what that role can do.
- Registration form: single column, inline validation as the user leaves each field (not only on submit).
- Submit button shows a loading state and disables itself while the request is in flight, to prevent double-submission.

### 6.6 Email Verification (`/verify-email`)
- Single focused screen: "We sent a code to [email]," a 6-digit code input, Verify button, Resend link (disabled with a countdown timer while rate-limited).
- No navigation away from this screen except logout — this matches the forced-step rule in the Product Spec.
- Error state: incorrect or expired code shown inline, code field clears and refocuses.

### 6.7 Login / Forgot Password / Reset Password
- Standard single-column forms. Login includes a "Forgot password?" link. Generic error message on failed login ("Incorrect email or password") — never reveal which field was wrong.

### 6.8 Freelancer Dashboard (`/freelancer/dashboard`)
- Profile completeness/publish status card at top (shows what's missing if not yet Live).
- Summary cards: new requests count, unread notifications count, current rating and completed jobs count.
- Empty state (brand new account): a checklist guiding the user through profile setup steps in order.

### 6.9 Edit Profile, Skills, Portfolio, Preview (`/freelancer/profile/*`)
- Edit Profile: sectioned single-column form (Basic info, Professional info, Availability, Pricing), each section independently savable so a long form doesn't force one giant submit.
- Skills: category multi-select, then skill tags appear per selected category as checkboxes; an "Other — propose a skill" option opens a small form (name + category) that submits for Admin review, shown afterward as a "Pending approval" badge, not yet a selectable tag.
- Portfolio: image grid with an upload button; drag-and-drop on desktop, tap-to-upload on mobile; reject non-JPG/PNG files inline with a clear message before upload attempts.
- Preview: renders the exact same component used on the public profile page, so what the freelancer sees is what an employer sees.

### 6.10 Hiring Requests — List and Detail (`/freelancer/requests`, `/employer/requests`, `/*/requests/:id`)
- List: grouped or filterable by state, most recent first, each row shows a state Badge.
- Detail: full request info, a visual state-progress indicator (Submitted → Pending → Accepted → Completed, with Declined/Cancelled as terminal off-path states), and the action buttons valid for the current state and current user's role only — invalid actions are not shown at all, not shown-but-disabled.
- Empty state: "No requests yet" — for Freelancer, a note that requests will appear here once an employer reaches out; for Employer, a link back to Discover.

### 6.11 Reviews (`/freelancer/reviews`, `/employer/reviews`)
- Freelancer view: read-only list with rating distribution summary at top.
- Employer view: list of own submitted reviews, each with Edit/Delete actions.
- Leave a Review (modal from Request Detail): a single interactive 1–5 star input plus an optional textarea, submit button disabled until a star rating is selected.

### 6.12 Notifications (`/freelancer/notifications`, `/employer/notifications`)
- Chronological list, unread items in full-color/bold per FR-048, read items faded.
- Tapping a notification marks it read and navigates to the relevant screen.
- Empty state: "No notifications yet."

### 6.13 Account Settings (`/*/settings/account`)
- Password change, contact preferences (Freelancer only), account deactivation — deactivation requires a confirmation modal, never a single click.

### 6.14 Admin Pages (`/admin/*`)
- Data-table-first layout: filterable, sortable tables for Users, Freelancers, Reports, Categories.
- Detail views open in a side panel or dedicated page (Recommendation: side panel for quick moderation actions, dedicated page for full profile review), with the relevant action buttons (dismiss/warn/suspend/remove; approve/reject/merge) always visible, not buried in a menu.

## 7. Forms — General Rules

- Every required field is marked; every field has a visible label, not just a placeholder (placeholders disappear on input and shouldn't be the only label).
- Validation errors appear inline, next to the field, in plain language ("Enter a valid email address," not "Invalid input: field email").
- Submit buttons disable and show a loading indicator during submission; re-enable on error.
- Destructive actions (delete portfolio item, delete review, deactivate account, admin suspend/remove) always require a confirmation step.

## 8. Loading States

- Skeleton screens (shaped placeholders matching final content) for feed, profile, and list pages — preferred over spinners since they reduce perceived wait time and prevent layout shift.
- Inline spinners only for button actions and small, isolated content areas (e.g. a single card refreshing).
- No page should show a completely blank screen while loading.

## 9. Empty States

Every list-type page has a defined empty state with a next action, not just an absence of content:

| Page | Empty State Message | Next Action |
|---|---|---|
| Home / Search / Category | No freelancers found | Browse all categories |
| Freelancer requests received | No requests yet | Explanation of how requests arrive |
| Employer requests sent | No requests yet | Link to Discover |
| Reviews (either side) | No reviews yet | Explanation of when reviews appear |
| Notifications | No notifications yet | — |
| Admin Reports queue | No open reports | — |
| Admin pending categories | No pending submissions | — |

## 10. Error States

| Scenario | Behavior |
|---|---|
| Network/server failure loading a page | Full-page error with a "Try again" button, not a blank screen |
| Form submission fails validation | Inline field errors, page does not navigate away |
| Form submission fails on the server | Toast with a plain-language message, form data is preserved, not cleared |
| Action attempted on stale/changed data (e.g. request already responded to) | Toast explaining the conflict, page refreshes to current state |
| Profile/resource no longer exists | Dedicated "no longer available" message, not a generic 404 |
| Unauthorized access attempt (wrong role hitting a protected route) | Redirect to the correct home screen for that role, not an error page |

## 11. Success States

- Destructive or significant actions (publish profile, send request, submit review, cancel request) confirm success with a toast or inline confirmation banner, not silence.
- State-changing actions (accept/decline a request, mark completed) visually update the state Badge immediately without requiring a manual page refresh.

## 12. Accessibility

- Color is never the only signal for state (badges pair color with text/icon, not color alone) — required since request states and verification badges rely on color coding.
- All interactive elements are reachable and operable via keyboard on desktop.
- All images (profile photos, portfolio items) have descriptive alt text (Recommendation: auto-generate from freelancer name and item context if the user doesn't supply a caption).
- Minimum tap target size of 44x44px on mobile for all buttons and interactive icons.
- Form errors are announced to screen readers, not only shown visually (`aria-live` region for validation summaries).

## 13. Open Items

- Exact color palette values (Section 5.1) are a Recommendation — a starting point, not a final brand decision.
- Admin detail view layout (side panel vs. dedicated page, Section 6.14) is a Recommendation pending real content volume testing.
- Infinite scroll vs. pagination split by breakpoint (Section 6.1) is a Recommendation, can be simplified to one approach across all breakpoints if preferred.
