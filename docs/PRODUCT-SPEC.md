# Product Specification — Hireskills

Answers: What should the application do?
Source: PRD-Product Requirements Document.docx, plus product owner decisions made during requirements analysis.
Status legend: Confirmed, Recommendation (default proposed, open to correction), Assumption (gap filled, flagged for awareness)

---

## 1. Overview

Hireskills is a localized freelancer discovery platform for Abuyog, Leyte. Freelancers create profiles describing skills, experience, and services. Employers browse, search, filter, and contact freelancers directly. There is no job-posting or application model — employers discover and reach out, freelancers respond. Direct communication happens off-platform through a channel the freelancer chooses (SMS, Messenger, email). A structured Hiring Request replaces a formal application process.

Product principle: don't make employers search through people; make it easy for them to discover the right kind of people. Discovery quality — categories, search, filters, ranking, and profile information — is the product.

## 2. Goals

- G1: Let an employer find a suitable local freelancer without asking around informally.
- G2: Let a freelancer be discoverable and contactable without needing existing social reach.
- G3: Keep the interaction model simple — no job board, no bidding, no in-platform payments.
- G4: Provide enough trust signals (verification, reviews, completed jobs) that an employer can decide with confidence.

## 3. Non-Goals (Out of Scope for v1)

- Employer job posting or public job listings
- Application/shortlisting workflow
- Integrated payments, escrow, or payroll
- AI-based matching (v1 uses rule-based search, filters, and ranking)
- In-app messaging (communication happens on external channels)
- Video calling
- Expansion beyond Abuyog, Leyte
- Corporate/bulk recruitment workflows

## 4. Users and Roles

Three roles. An account holds exactly one role, fixed at registration, never switched or combined.

| Role | Who | Capabilities |
|---|---|---|
| Guest | Anyone, not logged in | Browse feed, search, filter, view full freelancer profiles (minus contact details). Cannot act. |
| Freelancer | Local worker offering a skill/service | Build and publish a profile, receive and respond to Hiring Requests, receive reviews, report profiles |
| Employer | Person/business seeking to hire | Browse, search, filter, contact freelancers, send Hiring Requests, submit reviews, report profiles |
| Admin | Platform operator | Moderate users, categories, reports, verifications. Not created through public registration; provisioned manually. |

## 5. Geographic Scope

Single municipality: Abuyog, Leyte. Location is structured as Municipality + Barangay fields — no free text, no map, no geocoordinates in v1.

## 6. Feature Inventory

| Feature ID | Feature | Priority |
|---|---|---|
| FEAT-01 | Role selection | P0 |
| FEAT-02 | Freelancer registration | P0 |
| FEAT-03 | Employer registration | P0 |
| FEAT-04 | Freelancer profile | P0 |
| FEAT-05 | Categories and skills taxonomy | P0 |
| FEAT-06 | Discovery feed | P0 |
| FEAT-07 | Search | P0 |
| FEAT-08 | Filtering | P0 |
| FEAT-09 | Freelancer profile page | P0 |
| FEAT-10 | Portfolio | P0 |
| FEAT-11 | Contact / Hire button | P0 |
| FEAT-12 | Hiring request system | P0 |
| FEAT-13 | Reviews and ratings | P1 |
| FEAT-14 | Verification (email baseline, enhanced optional) | P0 baseline / P1 enhanced |
| FEAT-15 | Notifications | P0 |
| FEAT-16 | Report profile | P0 |
| FEAT-17 | Admin and moderation | P0 |
| FEAT-18 | Discovery/ranking logic | P0 |

## 7. Functional Requirements

### 7.1 Role Selection (FEAT-01)
- FR-001: At registration, user selects "I'm a Freelancer" or "I'm looking to hire."
- FR-002: Role is permanent for the account. No later switching or expansion.

### 7.2 Freelancer Registration (FEAT-02)
- FR-003: Required fields: phone number, email, name, municipality, barangay, password.
- FR-004: Optional fields: profile photo, short introduction, contact preferences.
- FR-005: Freelancer selects one or more categories; within each, selects from a predefined skill tag list. No free text for skills.
- FR-006: Profile is not live until email verification is complete.

### 7.3 Employer Registration (FEAT-03)
- FR-007: Required fields mirror the freelancer set: name, email, phone, municipality, barangay, password.
- FR-008: Email verification is forced immediately at registration for both roles. Employer must be email-verified to send a Hiring Request or submit a Review.

### 7.4 Freelancer Profile (FEAT-04)
- FR-009: Basic info: profile photo, full name, municipality, general location, short introduction.
- FR-010: Professional info: skills, services offered, years of experience, previous work, portfolio, certifications, licenses.
- FR-011: Availability: Available, Currently unavailable, Available on specific days.
- FR-012: Pricing: one or more of starting price, price range, hourly rate, per-project rate, "Contact for pricing."
- FR-013: Reputation (rating, review count, completed jobs, number of clients) is system-derived, never manually entered.
- FR-014: Contact/Hire action present on every live profile.
- FR-015: Freelancer can edit their profile anytime; skill/category edits don't need re-approval unless a new category is proposed.

### 7.5 Categories and Skills (FEAT-05)
- FR-016: Fixed categories: Home and Repair, Technology, Design and Education, Personal Services and Beauty, Events, Transportation, Other.
- FR-017: Each category has a fixed, predefined skill tag list.
- FR-018: A freelancer may belong to more than one category.
- FR-019: "Other" submissions require Admin approval before becoming a usable category/skill.
- FR-020: Only Admin creates, edits, retires, or merges categories and skills.

### 7.6 Discovery Feed (FEAT-06)
- FR-021: A feed of Live freelancer profiles is visible by default, no query or login required.
- FR-022: Tapping a profile opens the full view, for guests and logged-in users alike.

### 7.7 Search (FEAT-07)
- FR-023: Search matches name, skill, service, category, location. No login required.

### 7.8 Filtering (FEAT-08)
- FR-024: Filters: category, skill, municipality/barangay, experience, rating. No login required.
- FR-025: Search and filters combine in a single query.

### 7.9 Freelancer Profile Page (FEAT-09)
- FR-026: Key decision info (skills, availability, rating, verification badges) visible without deep navigation, to guests and logged-in users alike.
- FR-026a: A guest sees the entire profile — skills, portfolio, availability, pricing, ratings, badges — except the freelancer's actual contact channel details. Contact/Hire and Send Hiring Request are visible but disabled for guests; tapping either prompts login/registration.
- FR-027: "Report profile" action present on every profile, for logged-in users only.

### 7.10 Portfolio (FEAT-10)
- FR-028: Freelancers upload work examples (before/after photos, completed projects, designs).
- FR-029: JPG and PNG only. Recommendation: up to 10 items per profile, 5 MB per file (not yet confirmed).

### 7.11 Contact / Hire Button (FEAT-11)
- FR-030: Surfaces the freelancer's chosen external contact channel(s) — Gmail, Messenger, SMS.
- FR-031: Contacting does not itself create a Hiring Request; it's for pre-hiring discussion.

### 7.12 Hiring Request System (FEAT-12)
- FR-032: Form fields: service needed, preferred date, location, pricing mode, single free-text message (no reply thread).
- FR-033: States and transitions follow the state machine in Section 8.1 exactly.
- FR-034: If Freelancer doesn't respond to a Completed mark within 7 days, the Employer's mark stands automatically.
- FR-035: Either party can Cancel before Completed. Cancelled requests cannot be reviewed.

### 7.13 Reviews and Ratings (FEAT-13)
- FR-036: Only the Employer on a Completed request can review, only after Completed.
- FR-037: Maximum one review per completed request.
- FR-038: No reviews on Cancelled or Declined requests.
- FR-039: A review is a single overall rating (1–5) reflecting service quality, plus optional written feedback. No separate stored sub-dimension scores.
- FR-040: Employer can edit or delete their own review anytime.
- FR-041: No in-product dispute/reply mechanism for freelancers on a review.

### 7.14 Verification (FEAT-14)
- FR-042: Email verification is mandatory baseline verification for both roles, completed via OTP immediately at registration. Freelancer profile cannot go live without it. Phone number is collected as a contact field only — not itself verified in v1.
- FR-043: Enhanced, optional verification, completed later from the account: identity, certifications.
- FR-044: Profile shows an "Email verified" badge (baseline, all live profiles) and an "Identity verified" badge (enhanced, optional).

### 7.15 Notifications (FEAT-15)
- FR-045: Freelancers notified on: profile view/contact, new hiring request, accepted request, new review.
- FR-046: Employers notified on: freelancer response, freelancer accepting a request.
- FR-047: In-app only, delivered near-instantly, generated on every major state change.
- FR-048: Unread notifications shown with full-color emphasis; read notifications fade in contrast.

### 7.16 Report Profile (FEAT-16)
- FR-049: Any logged-in employer or freelancer can report a freelancer profile.
- FR-050: A report creates a record under Admin → Reports.
- FR-051: Admin actions on a report: dismiss, warn, suspend, or remove listing. No other action types.

### 7.17 Admin and Moderation (FEAT-17)
- FR-052: Admin manages users, freelancer profiles, categories, skills, reviews, reports, verification requests, suspended accounts.
- FR-053: Admin can feature or remove profiles per platform rules.

### 7.18 Discovery / Ranking Logic (FEAT-18)
- FR-054: Ranking signals: skill match, service match, location, availability, experience, reviews.
- FR-055: Result labeling must use neutral language ("Recommended for your search"), never a superlative claim ("Best electrician").

## 8. Business Rules

### 8.1 Hiring Request State Machine

Submitted → Pending Response → (optional) Request More Information → Accepted or Declined → (if Accepted) Completed or Cancelled

| State | Actor | Trigger | Result |
|---|---|---|---|
| Submitted | Employer | Sends request | Freelancer receives it |
| Pending Response | Freelancer | Reviews request | Chooses Accept, Request More Information, or Decline |
| Request More Information | Both | External clarification | Freelancer decides |
| Accepted | Freelancer | Agrees to proceed | Work can begin |
| Declined | Freelancer | Rejects request | Request ends, no review possible |
| Cancelled | Employer or Freelancer | Either cancels before Completed | Request ends, no review possible |
| Completed | Employer, confirmed by Freelancer | Employer marks done; Freelancer confirms or is auto-confirmed after 7 days of no response | Review becomes available |

### 8.2 Business Rules List

| ID | Rule |
|---|---|
| BR-001 | Only the transitions in the table above are valid; no other path is permitted. |
| BR-002 | A review can only be created from a Completed state. |
| BR-003 | One review per completed request, editable/removable by its author only. |
| BR-004 | A profile cannot be published (visible in discovery/search) without email verification. |
| BR-005 | Discovery result labeling must avoid absolute superlative claims. |
| BR-006 | Reputation metrics (rating, completed jobs, number of clients) are system-derived, never self-reported. |
| BR-007 | New categories/skills only enter the taxonomy through Admin approval of an "Other" submission. |

## 9. Non-Functional Requirements

| ID | Requirement |
|---|---|
| NFR-001 | Notifications delivered near-instantly (target: a few seconds from triggering event). |
| NFR-002 | Mobile-first responsive website, web-only, no native app. |
| NFR-003 | Expected scale: hundreds to low thousands of active users at launch (single municipality). |
| NFR-004 | No offline mode required. |
| NFR-005 | Passwords stored using a secure hashing algorithm; never stored or logged in plain text. |
| NFR-006 | A freelancer's actual contact channel details are shown only to logged-in, action-eligible users; all other profile content is visible to guests. |
| NFR-007 | Basic rate limiting on Hiring Request and Report submission to prevent abuse. |
| NFR-008 | Authentication uses server-side sessions, not token-based auth (JWT) — web-only, no native app planned. |
| NFR-009 | Development is solo/AI-assisted; technology stack avoids paid services or paid software. All interface language is English. |

## 10. Constraints

- No integrated payments, escrow, or payroll.
- No in-app messaging — communication happens on external, freelancer-chosen channels.
- No job posting or application/shortlist workflow.
- No AI-based matching — ranking is rule-based only.
- Geographic scope limited to Abuyog, Leyte.

## 11. User Flows

Condensed reference. Full step-by-step detail lives in the earlier User Flow Specification; this table is the quick-lookup version for implementation.

| Flow | User | Summary | Key Rules |
|---|---|---|---|
| Registration entry | Guest | Choose Freelancer or Employer, permanently | FR-001, FR-002 |
| Freelancer registration | Freelancer | Fill required fields, account created unverified, forced into email verification | FR-003, FR-004, FR-006 |
| Employer registration | Employer | Fill required fields, account created unverified, forced into email verification | FR-007, FR-008 |
| Login | Any registered user | Credentials → session. Unverified users routed to email verification instead of home screen | NFR-008 |
| Logout | Any logged-in user | Session terminated | — |
| Email verification | Freelancer/Employer | Forced OTP step right after registration; blocks all other screens until complete | FR-006, FR-008, FR-042, BR-004 |
| Build freelancer profile | Freelancer | Basic info, categories/skills, professional info, availability, pricing, portfolio; saved as draft | FR-005, FR-009–013, FR-016–019 |
| Publish freelancer profile | Freelancer | System checks: ≥1 category+skill, basic info complete, email verified. Passes → Live | FR-006, BR-004 |
| Edit freelancer profile | Freelancer | Edits save immediately; new "Other" proposals need Admin approval first | FR-015, FR-019 |
| Browse discovery feed | Guest/Employer | Ranked Live profiles, no login required | FR-021, FR-054, FR-055 |
| Search freelancers | Guest/Employer | Query matches name/skill/service/category/location | FR-023 |
| Filter freelancers | Guest/Employer | Category, skill, location, experience, rating; combinable with search | FR-024, FR-025 |
| View freelancer profile | Guest/Employer | Full profile shown; contact details hidden and actions disabled for guests | FR-026, FR-026a, FR-027 |
| Contact freelancer | Employer, verified | Reveals external contact channel(s); freelancer notified | FR-030, FR-031, FR-045 |
| Send hiring request | Employer, verified | Fill form → Submitted state; freelancer notified | FR-032, FR-033, BR-001 |
| Respond to hiring request | Freelancer | Accept / Request More Information / Decline | FR-033, BR-001 |
| Request more information | Both | Holding state; clarification happens externally; freelancer later decides | BR-001 |
| Mark request completed | Employer, confirmed by Freelancer | Employer marks done → Freelancer confirms or auto-confirms after 7 days | FR-034, BR-001 |
| Cancel hiring request | Employer or Freelancer | Moves to Cancelled from any pre-Completed state; blocks review | FR-035, BR-001 |
| Submit review | Employer | Single 1–5 rating + optional text, only from Completed, one per request | FR-036–039, BR-002, BR-003 |
| Edit/delete review | Employer (author) | Update or remove own review; reputation recalculated | FR-040, BR-003, BR-006 |
| Report a profile | Employer/Freelancer, logged in | Creates a record in Admin → Reports queue | FR-027, FR-049, FR-050 |
| Receive/read notifications | Freelancer/Employer | Real-time, unread/read visual states | FR-045–048 |
| Enhanced verification request | Freelancer, email verified | Submit identity/certification proof; Admin reviews | FR-043, FR-044 |
| Admin: review reported profile | Admin | Dismiss / warn / suspend / remove | FR-049–052 |
| Admin: approve new category/skill | Admin | Approve / reject / merge "Other" proposals | FR-019, FR-020, BR-007 |

## 12. Pages and Routes

### 12.1 Public (Guest-accessible, no login)

| Route | Page | Purpose |
|---|---|---|
| / | Home / Discovery Feed | Default landing, ranked Live profiles |
| /search | Search Results | Query-based results |
| /categories | Category Browse | Browse fixed category list |
| /categories/:categorySlug | Category Listing | Freelancers filtered to one category |
| /freelancers/:id | Freelancer Profile | Full profile, contact/action buttons disabled for guests |
| /register | Role Selection | Entry point choosing Freelancer or Employer |
| /register/freelancer | Freelancer Registration | Account creation |
| /register/employer | Employer Registration | Account creation |
| /verify-email | Email Verification | Forced OTP entry after registration |
| /login | Login | Credential entry |
| /forgot-password | Forgot Password | Request reset |
| /reset-password | Reset Password | Set new password via token |

### 12.2 Freelancer (Protected)

| Route | Page | Purpose |
|---|---|---|
| /freelancer/dashboard | Dashboard | Profile status, recent requests/reviews/notifications |
| /freelancer/profile/edit | Edit Profile | Basic/professional info, availability, pricing |
| /freelancer/profile/skills | Manage Skills and Categories | Category/skill selection, "Other" proposal |
| /freelancer/profile/portfolio | Manage Portfolio | Upload/remove JPG/PNG work samples |
| /freelancer/profile/preview | Profile Preview | View own profile as others see it |
| /freelancer/requests | Hiring Requests (Received) | List by state |
| /freelancer/requests/:id | Request Detail | Accept, Decline, Request Info, confirm Completed |
| /freelancer/reviews | Reviews Received | List + aggregate rating |
| /freelancer/verification | Verification Center | Email status; submit optional identity/certification |
| /freelancer/notifications | Notifications | Full list, read/unread |
| /freelancer/settings/account | Account Settings | Password, contact preferences, deactivation |

### 12.3 Employer (Protected)

| Route | Page | Purpose |
|---|---|---|
| /employer/requests | Hiring Requests (Sent) | List by state |
| /employer/requests/new | New Hiring Request | Tied to a specific freelancer |
| /employer/requests/:id | Request Detail | Track state, mark Completed, Cancel |
| /employer/reviews | Reviews Given | List, edit, delete |
| /employer/notifications | Notifications | Full list, read/unread |
| /employer/settings/account | Account Settings | Password, deactivation |

Logged-in Employer has no separate dashboard — Home (`/`) serves as the authenticated view with actions enabled.

### 12.4 Shared Actions (modal/panel, not standalone routes)

| Action | Location | Access |
|---|---|---|
| Contact Freelancer | Freelancer Profile | Employer, email verified |
| Report Profile | Freelancer Profile | Employer or Freelancer, logged in |
| Leave a Review | Request Detail (Completed) | Employer, request owner |

### 12.5 Admin (Protected, Elevated)

| Route | Page | Purpose |
|---|---|---|
| /admin/dashboard | Admin Dashboard | Pending reports, category proposals, verifications overview |
| /admin/users | User Management | List all accounts |
| /admin/users/:id | User Detail | View, suspend/reactivate |
| /admin/freelancers | Freelancer Moderation | List all profiles, feature/remove |
| /admin/freelancers/:id | Freelancer Detail (Admin) | Full profile + moderation actions |
| /admin/categories | Category and Skill Management | Edit taxonomy |
| /admin/categories/pending | Pending "Other" Submissions | Approve, reject, merge |
| /admin/reports | Reports Queue | All reports |
| /admin/reports/:id | Report Detail | Dismiss, warn, suspend, remove listing |
| /admin/verifications | Verification Queue | Review identity/certification submissions |
| /admin/reviews | Review Moderation | View/remove violating reviews |

### 12.6 Route Authorization Rules

- `/freelancer/*` requires session role = Freelancer.
- `/employer/*` requires session role = Employer.
- `/admin/*` requires session role = Admin. No other role reaches it under any condition.
- `/employer/requests/new` requires the Employer's email to be verified; unverified users redirect to `/verify-email`.
- Leave a Review only reachable from a Completed Request Detail owned by the requesting Employer.
- `/verify-email` only reachable by an unverified account; a verified user hitting it redirects to their dashboard.

## 13. Decisions and Assumptions Log

| ID | Decision/Assumption | Status | Notes |
|---|---|---|---|
| D-01 | Guest browsing allowed; login required only for actions | Confirmed | Product owner decision |
| D-02 | One role per account, fixed at registration, no switching | Confirmed | Product owner decision |
| D-03 | Email OTP is baseline verification, replacing phone OTP | Confirmed | Product owner decision — avoids paid SMS gateway dependency |
| D-04 | Portfolio accepts JPG and PNG only | Confirmed | Product owner decision |
| D-05 | Review is a single overall 1–5 rating, no sub-dimension scores | Confirmed | Product owner decision |
| D-06 | Municipality + barangay only, no map/geocoordinates in v1 | Confirmed | Product owner decision |
| D-07 | Admin report actions: dismiss, warn, suspend, remove listing | Confirmed | Product owner decision |
| D-08 | Session-based auth, not JWT | Confirmed | Product owner decision — web-only |
| D-09 | Scale target: hundreds to low thousands of users | Confirmed | Product owner decision |
| D-10 | No paid services or software; English-only interface | Confirmed | Product owner decision — drives Technical Specification |
| D-11 | Portfolio count/size limit: 10 items, 5 MB each | Recommendation | Not yet confirmed by product owner |
| D-12 | Employer has no separate dashboard; Home serves logged-in view | Recommendation | Avoids a redundant duplicate page |
| D-13 | Contact/Report/Review are modals, not standalone routes | Recommendation | Short actions tied to a parent page |
| D-14 | No auto-expiry for requests stuck in Pending Response or Request More Information | Gap | Not defined in PRD; left open, revisit post-MVP |
| D-15 | No dispute mechanism if Freelancer contests a Completed mark | Gap | Not defined in PRD; left open, revisit post-MVP |
| D-16 | No defined behavior when a freelancer has zero contact channels configured | Gap | Recommendation: block Contact action, prompt Send Hiring Request instead |
| D-17 | No stated limit on concurrent open requests from one Employer to one Freelancer | Gap | Recommendation: no limit in v1 |

## 14. Acceptance Criteria (Product Level)

- AC-01: A new freelancer can register, verify email, build a profile with ≥1 category/skill pair, and go live only after email verification.
- AC-02: A guest or employer can find a freelancer through search or filters without login.
- AC-03: An employer can send a Hiring Request and track it through every defined state without ambiguity, after email verification.
- AC-04: A review cannot be created except from a Completed request, never more than once per request.
- AC-05: An unverified freelancer profile never appears in discovery, search, or filter results.
- AC-06: All ranking/result language avoids superlative claims.
- AC-07: A guest sees every part of a freelancer profile except contact details; Contact/Hire and Send Hiring Request prompt login when tapped by a guest.
