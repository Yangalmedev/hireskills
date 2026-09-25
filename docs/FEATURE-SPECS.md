# Feature Specifications — Hireskills

Answers: What exactly should Claude build next?
Depends on: Product Specification, Technical Specification, UI/UX Specification
Build one feature at a time, in the order listed. Each feature is a self-contained implementation unit — read its full entry, plus the referenced sections of the other three documents, before writing code for it.

---

## Build Order

| # | Feature | Depends On |
|---|---|---|
| FEATURE-001 | Authentication Foundation | — |
| FEATURE-002 | Email Verification | FEATURE-001 |
| FEATURE-003 | Password Reset | FEATURE-001 |
| FEATURE-004 | Freelancer Profile Management | FEATURE-001, FEATURE-002 |
| FEATURE-005 | Categories and Skills Taxonomy | FEATURE-004 |
| FEATURE-006 | Portfolio Management | FEATURE-004 |
| FEATURE-007 | Profile Publishing | FEATURE-004, FEATURE-005 |
| FEATURE-008 | Discovery Feed | FEATURE-007 |
| FEATURE-009 | Search | FEATURE-008 |
| FEATURE-010 | Filtering | FEATURE-008 |
| FEATURE-011 | Freelancer Profile Page | FEATURE-007 |
| FEATURE-012 | Contact / Hire Action | FEATURE-011 |
| FEATURE-013 | Hiring Request System | FEATURE-011 |
| FEATURE-014 | Reviews and Ratings | FEATURE-013 |
| FEATURE-015 | Notifications | FEATURE-013 |
| FEATURE-016 | Report Profile | FEATURE-011 |
| FEATURE-017 | Enhanced Verification | FEATURE-004 |
| FEATURE-018 | Discovery Ranking Logic | FEATURE-008 |
| FEATURE-019 | Admin: User and Freelancer Moderation | FEATURE-001 |
| FEATURE-020 | Admin: Category and Skill Approval | FEATURE-005 |
| FEATURE-021 | Admin: Reports Queue | FEATURE-016 |

---

## FEATURE-001 — Authentication Foundation

**Purpose:** Let a person create a Freelancer or Employer account and log in/out with a session.
**User:** Guest → Freelancer or Employer

**Requirements:** FR-001, FR-002, FR-003, FR-004, FR-007, NFR-005, NFR-008

**UI:**
- `/register` — role selection (Product Spec 12.1; UI/UX Spec 6.5)
- `/register/freelancer`, `/register/employer` — registration forms
- `/login` — login form

**Routes:** See Technical Spec Section 5.1. Login/logout use Fortify's default routes and controllers as-is. Registration and OTP verification are customized.

**Database:** users table (Technical Spec 3.2). No other tables involved for Freelancer registration except the matching freelancer_profiles row, created alongside the User (Technical Spec Section 4.1).

**Implementation note:** The starter kit already scaffolds `App\Actions\Fortify\CreateNewUser`. Extend this action to accept role, phone, municipality, and barangay, and to create the FreelancerProfile row when role = freelancer — do not write a parallel registration action from scratch. Login and logout already work out of the box via Fortify; this feature's real work is registration and its downstream effects.

**Business Rules:**
- Role is set once at registration and never changed (FR-002).
- Freelancer and Employer registration fields are separate forms but share the same underlying required-field set except role.
- 2FA, Passkeys, and Password Confirmation are present in the scaffolded codebase (Technical Spec Section 4.2a) but out of scope — do not surface them in the UI or route table.

**Validation:**
- Email: valid format, unique across all Users.
- Password: minimum length and complexity (Recommendation: 8+ characters, at least one letter and one number).
- Phone: required, format-validated, not uniqueness-checked (multiple accounts could share a household phone — not blocked).
- All required fields (name, municipality, barangay) non-empty.

**Errors:**
- Duplicate email → 409, field-level message "Email is already registered."
- Invalid credentials on login → 401, generic "Incorrect email or password" (never reveal which field failed).
- Suspended account attempting login → 403, message states the account is suspended.

**Acceptance Criteria:**
- A new user can register as either role and receive a session.
- A registered user can log in and log out.
- Passwords are never stored or logged in plaintext.
- Login does not reveal whether the failure was the email or the password.

**Dependencies:** None — build first.

---

## FEATURE-002 — Email Verification

**Purpose:** Force baseline trust verification via email OTP immediately after registration, before any other screen is usable.
**User:** Freelancer, Employer

**Requirements:** FR-006, FR-008, FR-042, FR-044, BR-004, NFR-007

**UI:** `/verify-email` (UI/UX Spec 6.6) — forced, no navigation away except logout.

**Routes:** See Technical Spec Section 5.1 (`/email/verify-otp`, `/email/resend-otp`). These replace Fortify's default link-based verification routes — Fortify ships email verification, but as a clickable magic link, not an entered code, so this feature overrides that piece specifically rather than reusing it as-is.

**Database:** email_otps table; updates users.email_verified_at.

**Business Rules:**
- Registration (FEATURE-001) always redirects here before any other route is reachable.
- A FreelancerProfile cannot be published until email_verified_at is set (BR-004).
- An Employer cannot send a Hiring Request or submit a Review until email_verified_at is set (FR-008).
- Every protected route checks verification status server-side, not just client-side redirect.

**Validation:**
- OTP code: 6 digits, must match the hashed, non-expired, unconsumed EmailOtp row for that user.

**Errors:**
- Wrong code → 400, "Incorrect code, try again."
- Expired code → 400, "Code expired, request a new one."
- Resend requested before rate-limit window elapses → 429, with retry-after time shown as a countdown in the UI.

**Acceptance Criteria:**
- A newly registered user cannot reach any page other than /verify-email until they enter a correct, unexpired code.
- Resend is rate-limited per NFR-007.
- Once verified, Freelancer routes to profile setup, Employer routes to Home.

**Dependencies:** FEATURE-001.

---

## FEATURE-003 — Password Reset

**Purpose:** Let a user recover access if they forget their password.
**User:** Freelancer, Employer (Admin resets are handled manually, out of scope here)

**Requirements:** Derived from standard auth needs (Technical Spec 4.3), not directly PRD-sourced — flagged Recommendation.

**UI:** `/forgot-password`, `/reset-password` (UI/UX Spec 6.7)

**API:**
**Routes:** GET,POST /forgot-password; GET,POST /reset-password — Fortify defaults, used largely as-is (Technical Spec Section 5.1, 4.3).

**Database:** Reuses a token pattern similar to EmailOtp (Recommendation: add a PasswordResetToken table, same shape as EmailOtp).

**Business Rules:**
- Requesting a reset never reveals whether the email exists (respond identically either way) to avoid account enumeration.
- A successful reset invalidates all existing sessions for that user (Recommendation).

**Validation:**
- New password meets the same complexity rule as registration.
- Token must be valid, unexpired, unused.

**Errors:**
- Invalid/expired token → 400, "This reset link is no longer valid, request a new one."

**Acceptance Criteria:**
- A user can request a reset, receive an email, set a new password, and log in with it.
- An expired or reused token is rejected.

**Dependencies:** FEATURE-001.

---

## FEATURE-004 — Freelancer Profile Management

**Purpose:** Let a Freelancer build and maintain their profile content.
**User:** Freelancer

**Requirements:** FR-009 to FR-013, FR-015

**UI:** `/freelancer/dashboard`, `/freelancer/profile/edit`, `/freelancer/profile/preview` (UI/UX Spec 6.8, 6.9)

**Routes:** GET /freelancer/profile/edit → FreelancerProfileController@edit (Inertia page); PUT /freelancer/profile → FreelancerProfileController@update (Technical Spec Section 5.2).

**Database:** FreelancerProfile table. Created (empty/draft) alongside the User row at registration, or lazily on first access — Recommendation: create at registration to simplify the one-to-one relationship.

**Business Rules:**
- Reputation fields (rating_avg, rating_count, completed_jobs_count, clients_count) are read-only from this endpoint — never accepted as input (BR-006). They are written only by the Reviews and Hiring Request features.
- Editing basic/professional info never requires re-verification or re-approval, except skill/category changes (handled in FEATURE-005).

**Validation:**
- Pricing fields: if pricing_mode requires a value (starting_price, price_range, hourly, per_project), price_min (and price_max for range) must be a positive number. "Contact for pricing" requires no value.
- Introduction: reasonable max length (Recommendation: 500 characters).
- Experience years: non-negative integer.

**Errors:**
- Invalid pricing combination (e.g. price_range with only price_min) → 400, field-level message.

**Acceptance Criteria:**
- A Freelancer can save each profile section independently.
- Reputation values never change through this feature's endpoints.
- Preview page renders identically to the public profile component.

**Dependencies:** FEATURE-001, FEATURE-002.

---

## FEATURE-005 — Categories and Skills Taxonomy

**Purpose:** Let a Freelancer select from the fixed category/skill taxonomy, and propose new ones through "Other."
**User:** Freelancer (selection), Admin (approval — see FEATURE-020)

**Requirements:** FR-005, FR-016 to FR-020, BR-007

**UI:** `/freelancer/profile/skills` (UI/UX Spec 6.9)

**Routes:** GET /categories → CategoryController@index; GET /categories/{category} → CategoryController@show (for skill listing within a category); PUT /freelancer/profile/skills → FreelancerSkillController@update (Technical Spec Section 5.2, 5.4).

**Database:** Category, Skill, FreelancerSkill tables.

**Business Rules:**
- Free text is never accepted as a skill value directly on a freelancer profile — only selection from approved Skill rows (FR-005).
- An "Other" proposal creates a new Skill row with status = pending, linked to the submitting freelancer via proposed_by_user_id; it does not become selectable by others and does not appear on the submitter's public profile until Admin approves it (FEATURE-020).
- A freelancer must have at least one approved category+skill pair to be publishable (checked in FEATURE-007).

**Validation:**
- At least one skill selected per selected category.
- "Other" proposal: name required, must map to an existing category, duplicate-name check against existing Skill rows in that category (case-insensitive) before creating a new pending row.

**Errors:**
- Attempt to select a skill outside any chosen category → 400.
- Attempt to remove the last skill from the last category, leaving zero skills, while the profile is already Live → 409, "A live profile needs at least one skill — add a replacement before removing this one," or the profile is automatically unpublished (Recommendation: block the removal instead, simpler for the user).

**Acceptance Criteria:**
- A Freelancer can select multiple categories and, within each, multiple predefined skills.
- Proposing an "Other" skill shows as "Pending approval" and is not usable until approved.
- No free-text value is ever stored as a selectable skill.

**Dependencies:** FEATURE-004.

---

## FEATURE-006 — Portfolio Management

**Purpose:** Let a Freelancer upload visual work examples.
**User:** Freelancer

**Requirements:** FR-028, FR-029

**UI:** `/freelancer/profile/portfolio` (UI/UX Spec 6.9)

**Routes:** POST /freelancer/portfolio → PortfolioController@store; DELETE /freelancer/portfolio/{portfolioItem} → PortfolioController@destroy (Technical Spec Section 5.3).

**Database:** PortfolioItem table. Files stored via the local filesystem storage described in Technical Spec Section 1/7.

**Business Rules:**
- JPG and PNG only, enforced server-side by validating file content/MIME type, not just the extension (Technical Spec Section 6).
- Recommendation: maximum 10 items per profile, 5 MB per file — pending product owner confirmation (Product Spec D-11).

**Validation:**
- File type and size checked before accepting the upload.
- Reject if the item count limit is already reached.

**Errors:**
- Wrong file type → 400, "Only JPG and PNG images are supported," shown before upload attempt where possible (client-side pre-check) and re-validated server-side.
- File too large → 400, states the limit.
- Item limit reached → 400, "Remove an item before adding another."

**Acceptance Criteria:**
- A Freelancer can upload and remove portfolio images.
- Non-JPG/PNG files are rejected with a clear message.
- Portfolio images render correctly on both the edit page and the public profile.

**Dependencies:** FEATURE-004.

---

## FEATURE-007 — Profile Publishing

**Purpose:** Gate a Freelancer profile's visibility behind minimum completeness and email verification.
**User:** Freelancer

**Requirements:** FR-006, BR-004

**UI:** Publish action from `/freelancer/dashboard` or `/freelancer/profile/edit` (UI/UX Spec 6.8).

**Routes:** POST /freelancer/profile/publish → FreelancerProfileController@publish (Technical Spec Section 5.2).

**Database:** Updates FreelancerProfile.is_published.

**Business Rules:**
- Publish succeeds only if: email_verified_at is set, at least one category with at least one approved skill exists, and basic info (name, municipality, barangay — inherited from User; photo/intro are optional per FR-004) is present.
- is_published = false profiles are excluded from every discovery, search, and filter query (BR-004) — enforced at the query layer, not just at the UI.
- A Freelancer can unpublish their own profile at any time (Recommendation, not explicitly in PRD — reasonable default for a freelancer going on leave).

**Validation:** N/A beyond the business-rule checks above; this endpoint runs checks, not form validation.

**Errors:**
- Any unmet condition → 400, with a list of exactly which conditions are unmet, matching the UI/UX Spec 6.4 requirement that the system "shows exactly what is missing."

**Acceptance Criteria:**
- A profile cannot go Live without email verification and at least one category/skill pair.
- An unpublished profile never appears in FEATURE-008/009/010 results.

**Dependencies:** FEATURE-004, FEATURE-005.

---

## FEATURE-008 — Discovery Feed

**Purpose:** Show a browsable, ranked list of Live freelancer profiles to anyone, logged in or not.
**User:** Guest, Employer

**Requirements:** FR-021, FR-022

**UI:** `/` (UI/UX Spec 6.1)

**Routes:** GET / → HomeController@index (Technical Spec Section 5.4), no query params applied for the default ranked list.

**Database:** Reads FreelancerProfile where is_published = true, joined with User for name/location.

**Business Rules:**
- No login required (guest browsing is Confirmed, Product Spec D-01).
- Ranking applied per FEATURE-018.
- Unpublished profiles are excluded unconditionally.

**Validation:** N/A (read-only, no input beyond pagination).

**Errors:** None beyond standard server-error handling.

**Acceptance Criteria:**
- A guest, with no session, sees the same feed content as a logged-in Employer.
- The feed never includes an unpublished profile, even via direct pagination manipulation.

**Dependencies:** FEATURE-007.

---

## FEATURE-009 — Search

**Purpose:** Let anyone find freelancers by name, skill, service, category, or location.
**User:** Guest, Employer

**Requirements:** FR-023

**UI:** `/search` (UI/UX Spec 6.2)

**Routes:** GET /search → SearchController@index, `q` query parameter (Technical Spec Section 5.4).

**Database:** Query against FreelancerProfile/User/Skill, matched on name, skill name, category name, municipality/barangay. (Recommendation: simple ILIKE/text matching for v1 scale — no need for a dedicated search engine at hundreds-to-low-thousands scale.)

**Business Rules:** Same is_published and ranking rules as FEATURE-008.

**Validation:** Query string sanitized to prevent injection (handled by Prisma parameterization, Technical Spec Section 6); empty query falls back to FEATURE-008 behavior.

**Errors:** None beyond standard server-error handling; zero results is not an error (see UI/UX Spec Section 9).

**Acceptance Criteria:**
- Searching by a skill name, category name, or location term returns matching Live profiles.
- No results shows the defined empty state, not a blank page.

**Dependencies:** FEATURE-008.

---

## FEATURE-010 — Filtering

**Purpose:** Let anyone narrow results by category, skill, location, experience, or rating.
**User:** Guest, Employer

**Requirements:** FR-024, FR-025

**UI:** Filter panel/sheet on `/`, `/search`, `/categories/:slug` (UI/UX Spec 6.1–6.3)

**Routes:** Query parameters (`category`, `skill`, `municipality`, `barangay`, `min_experience`, `min_rating`) accepted by both HomeController@index and SearchController@index (Technical Spec Section 5.4).

**Database:** Same source as FEATURE-008/009, with additional WHERE clauses.

**Business Rules:** Filters combine with an active search query (AND logic) per FR-025. Conflicting filters simply return zero results, not an error.

**Validation:** Filter values validated against known category/skill/location values; unrecognized values are ignored rather than erroring, to keep the UI forgiving.

**Errors:** None beyond standard server-error handling.

**Acceptance Criteria:**
- Filters can be combined with each other and with a search query.
- Clearing filters returns to the unfiltered result set.

**Dependencies:** FEATURE-008.

---

## FEATURE-011 — Freelancer Profile Page

**Purpose:** Show the full profile, with content and actions scoped correctly by viewer type.
**User:** Guest, Employer, Freelancer (viewing another's profile)

**Requirements:** FR-026, FR-026a, FR-027

**UI:** `/freelancers/:id` (UI/UX Spec 6.4)

**Routes:** GET /freelancers/{freelancer} → FreelancerShowController@show (Technical Spec Section 5.4).

**Database:** Reads FreelancerProfile, User, FreelancerSkill/Skill, PortfolioItem, aggregate review data.

**Business Rules:**
- The API response itself omits contact fields unless the requester is a logged-in, email-verified Employer or an Admin — this is a server-side response-shaping rule, not a UI-only hide (NFR-006).
- Contact/Hire and Send Hiring Request buttons render for everyone but are wired to prompt login for a Guest, per FR-026a.
- Report action renders only for logged-in users.

**Validation:** N/A (read endpoint).

**Errors:** Profile not found or unpublished → 404, rendered as the "no longer available" state (UI/UX Spec 6.4), not a generic error page.

**Acceptance Criteria:**
- A Guest sees every profile section except contact details.
- A Guest tapping Contact/Hire or Send Hiring Request is routed to login/registration, not shown an error.
- An Employer's request for this endpoint only receives contact details if their email is verified.

**Dependencies:** FEATURE-007.

---

## FEATURE-012 — Contact / Hire Action

**Purpose:** Reveal a freelancer's external contact channel(s) to a verified Employer and notify the freelancer.
**User:** Employer (verified)

**Requirements:** FR-030, FR-031, FR-045

**UI:** Contact modal from `/freelancers/:id` (UI/UX Spec 6.4)

**Routes:** POST /freelancers/{freelancer}/contact → ContactController@store (Technical Spec Section 5.5).

**Database:** Reads FreelancerProfile contact channel data (stored on FreelancerProfile or User — Recommendation: a small JSON field or dedicated columns for preferred channel links, e.g. `contact_channels: { gmail?, messenger?, sms? }`); writes a Notification row.

**Business Rules:**
- This action does not create a HiringRequest (FR-031) — it is purely informational plus a notification trigger.
- Requires session role = Employer and email_verified_at set; a Guest or unverified Employer hitting this endpoint directly gets a 401/403, not contact data.
- If the freelancer has zero configured contact channels, the action is blocked and the UI redirects to Send Hiring Request instead (Product Spec D-16, Recommendation).

**Validation:** N/A beyond the authorization check above.

**Errors:**
- No contact channel configured → 400, "This freelancer hasn't set up direct contact — send a hiring request instead."

**Acceptance Criteria:**
- A verified Employer sees the freelancer's contact channel(s) and the freelancer receives a notification.
- A Guest or unverified Employer cannot retrieve contact data through this endpoint under any request manipulation.

**Dependencies:** FEATURE-011.

---

## FEATURE-013 — Hiring Request System

**Purpose:** Implement the full Hiring Request lifecycle exactly as the state machine defines it.
**User:** Employer (creates, cancels), Freelancer (responds, confirms, cancels)

**Requirements:** FR-032 to FR-035, BR-001, FR-034

**UI:** `/employer/requests/new`, `/*/requests`, `/*/requests/:id` (UI/UX Spec 6.10)

**Routes:** POST /requests, GET /requests, GET /requests/{hiringRequest}, plus one POST route per transition — /requests/{hiringRequest}/accept, /decline, /request-info, /complete, /confirm-complete, /cancel, all on HiringRequestController (Technical Spec Section 5.6).

**Database:** HiringRequest table.

**Business Rules:**
- State machine (Product Spec Section 8.1) is enforced entirely in the service layer. Every action endpoint validates the current state before applying a transition; an invalid transition returns 409, never silently no-ops.
- Only the request's Employer can create, mark Completed, or cancel from the employer side.
- Only the request's Freelancer can accept, decline, request info, or confirm completion.
- Either party can cancel from any pre-Completed state (FR-035).
- 7-day auto-confirm: a scheduled job (Recommendation: a daily cron/cleanup task, or a check performed lazily whenever the request is read after the 7-day window has passed) finalizes any request whose completed_marked_at is more than 7 days old and completed_confirmed_at is still null.
- A completed_confirmed_at value, once set (by either explicit confirmation or auto-confirm), makes the request eligible for review (FEATURE-014).

**Validation:**
- Request creation form: service_needed, location, pricing_mode required; message required; preferred_date optional.
- Every action endpoint re-validates that the request is in the expected prior state before transitioning.

**Errors:**
- Action attempted on a request not in the valid prior state (e.g. accepting an already-Declined request) → 409, "This request has already been [state] — refresh to see its current status."
- Non-owner attempting an action → 403.

**Acceptance Criteria:**
- The state machine in Product Spec Section 8.1 is the only path through which a request's state changes — no endpoint allows skipping a state.
- The 7-day auto-confirm rule finalizes Completed status without requiring the Freelancer to act.
- Cancellation is possible from any state before Completed and blocks any future review on that request.

**Dependencies:** FEATURE-011.

---

## FEATURE-014 — Reviews and Ratings

**Purpose:** Let an Employer rate a freelancer after a completed job, and maintain accurate aggregate reputation.
**User:** Employer

**Requirements:** FR-036 to FR-041, BR-002, BR-003, BR-006

**UI:** Leave a Review modal from Request Detail; `/freelancer/reviews`, `/employer/reviews` (UI/UX Spec 6.11)

**Routes:** POST /reviews, PUT /reviews/{review}, DELETE /reviews/{review} on ReviewController (Technical Spec Section 5.7). Review lists are included as props on the profile and reviews pages, not a separate route.

**Database:** Review table; recalculates FreelancerProfile.rating_avg, rating_count, completed_jobs_count, clients_count on every write/delete.

**Business Rules:**
- Review creation only allowed when the referenced HiringRequest.state = completed and completed_confirmed_at is set (BR-002).
- One review per HiringRequest, enforced by the unique constraint on Review.hiring_request_id (FR-037).
- No review possible on cancelled or declined requests (FR-038) — enforced by the state check above, since those states never reach completed.
- Editing/deleting is restricted to the review's original author (FR-040).
- No freelancer-side reply/dispute mechanism exists (FR-041) — do not build one.
- Reputation fields on FreelancerProfile are recalculated synchronously whenever a review is created, edited, or deleted (BR-006); completed_jobs_count and clients_count are derived from HiringRequest.state = completed records, not from Review records, since a completed job doesn't require a review to count.

**Validation:**
- rating: integer 1–5, required.
- comment: optional, reasonable max length (Recommendation: 1000 characters).

**Errors:**
- Attempt to review a non-Completed/Cancelled/Declined request → 400/409.
- Attempt a second review on the same request → 409, "You've already reviewed this request."
- Non-author attempting edit/delete → 403.

**Acceptance Criteria:**
- A review can only be created from a Completed request.
- Editing or deleting a review recalculates the freelancer's displayed rating immediately.
- No UI or API path exists for a freelancer to respond to a review.

**Dependencies:** FEATURE-013.

---

## FEATURE-015 — Notifications

**Purpose:** Notify users in-app, near-instantly, on defined state changes.
**User:** Freelancer, Employer

**Requirements:** FR-045 to FR-048, NFR-001

**UI:** `/freelancer/notifications`, `/employer/notifications`, nav badge counts (UI/UX Spec 6.12, Section 3)

**Routes:** GET /notifications → NotificationController@index; PATCH /notifications/{notification}/read → NotificationController@markRead (Technical Spec Section 5.9). Unread count is served by the lightweight GET /api/notifications/unread-count JSON endpoint (Technical Spec Section 5.10).

**Database:** Notification table, written by other features (FEATURE-012 contact, FEATURE-013 request state changes, FEATURE-014 new review) rather than having its own creation endpoint.

**Business Rules:**
- Triggering events, per role (Product Spec Section 7.15):
  - Freelancer notified on: profile contacted, new hiring request, request accepted (by them — confirmation of their own action is optional; primary case is employer-facing), new review.
  - Employer notified on: freelancer responds (accept/decline/request-info), freelancer accepts a request.
- Every notification write happens inside the same transaction/service call as the triggering event, not as an afterthought — so a failed notification write should not silently lose the underlying state change, and vice versa (Recommendation: notification failures are logged but never block the primary action).
- Unread state defaults true; PATCH to read is the only way to clear it.

**Validation:** N/A (system-generated, not user input, except the mark-read action which only needs a valid notification id owned by the requester).

**Errors:** Marking a notification not owned by the requester as read → 403.

**Acceptance Criteria:**
- Every state change listed in Product Spec Section 7.15 produces exactly one notification to the correct recipient.
- Unread notifications are visually distinct (UI/UX Spec Section 9) until opened.

**Dependencies:** FEATURE-013 (most triggers originate here), FEATURE-012, FEATURE-014.

---

## FEATURE-016 — Report Profile

**Purpose:** Let a logged-in user flag a freelancer profile for Admin review.
**User:** Freelancer, Employer

**Requirements:** FR-049, FR-050

**UI:** Report modal from `/freelancers/:id` (UI/UX Spec 6.4)

**Routes:** POST /reports → ReportController@store (Technical Spec Section 5.8).

**Database:** Report table, status defaults to open.

**Business Rules:**
- Requires a logged-in session (any role except Admin — Admin doesn't report, it moderates).
- A duplicate report from the same user on the same profile within a short window may be blocked or merged (Recommendation, not yet confirmed — simplest v1 behavior: allow it, let Admin see multiple reports against one profile as a stronger signal).

**Validation:** reason required (from a short predefined list — Recommendation: fake profile, inappropriate content, scam/fraud, other); details optional free text.

**Errors:** None beyond standard validation.

**Acceptance Criteria:**
- A submitted report appears in the Admin Reports queue (FEATURE-021) with status open.
- A Guest cannot submit a report.

**Dependencies:** FEATURE-011.

---

## FEATURE-017 — Enhanced Verification

**Purpose:** Let a Freelancer optionally submit identity or certification proof for Admin review, earning a badge.
**User:** Freelancer

**Requirements:** FR-043, FR-044

**UI:** `/freelancer/verification` (Product Spec 12.2)

**Routes:** GET /freelancer/verification → VerificationController@index; POST /freelancer/verification → VerificationController@store (Recommendation, not yet in Technical Spec Section 5 — add alongside the existing route tables). Admin review routes: POST /admin/verifications/{verificationRequest}/approve, /reject (Technical Spec Section 5.11).

**Database:** VerificationRequest table; approval sets FreelancerProfile.identity_verified_at.

**Business Rules:**
- Only reachable after baseline email verification (FEATURE-002) is complete.
- Optional — never blocks profile publishing (FEATURE-007).
- Rejection notifies the freelancer with the ability to resubmit (treated as a new VerificationRequest row).

**Validation:** File upload required; type/size limits (Recommendation: same JPG/PNG/PDF-type constraints as reasonable for identity documents — confirm with product owner before implementation, since this wasn't explicitly covered by the portfolio file-type decision).

**Errors:** Missing file → 400.

**Acceptance Criteria:**
- A Freelancer can submit identity or certification proof independent of their publish status.
- Approval displays the "Identity verified" badge on the public profile.

**Dependencies:** FEATURE-004.

---

## FEATURE-018 — Discovery Ranking Logic

**Purpose:** Order discovery/search/filter results using the defined signals, without implying any single "best" result.
**User:** System (backs FEATURE-008, 009, 010)

**Requirements:** FR-054, FR-055

**UI:** Result ordering is invisible as a distinct UI, but result labeling text ("Recommended for your search") appears above result lists (UI/UX Spec 6.1).

**API:** Internal to the /api/freelancers query handler — not a separate endpoint.

**Database:** Reads FreelancerProfile fields: is_published, availability_status, experience_years, rating_avg, rating_count, plus FreelancerSkill match against the query/filter.

**Business Rules:**
- Ranking signals, in the order the PRD lists them: skill match, service match, location, availability, experience, reviews (FR-054). Exact weighting is a Recommendation, not specified by the PRD — starting proposal: skill/service match is a hard filter (non-matching profiles excluded, not just down-ranked), then sort by a composite score favoring Available status, higher rating, and more completed jobs, with location proximity (same barangay > same municipality) as a tiebreaker.
- UI copy anywhere ranked results are shown must use neutral language ("Recommended for your search") — never a superlative claim (FR-055, BR-005). This is a content rule as much as a logic rule — flag any hardcoded "Best" or "Top" string in the UI as a violation.

**Validation:** N/A (internal logic, not user input).

**Errors:** N/A.

**Acceptance Criteria:**
- Two otherwise-similar profiles never both claim to be "the best" in the same result set — verify no UI copy uses superlative language.
- A Live, Available profile with a matching skill and strong reviews consistently ranks above a Live but non-matching or lower-rated profile.

**Dependencies:** FEATURE-008.

---

## FEATURE-019 — Admin: User and Freelancer Moderation

**Purpose:** Let Admin view accounts and profiles and take moderation action.
**User:** Admin

**Requirements:** FR-052, FR-053

**UI:** `/admin/users`, `/admin/users/:id`, `/admin/freelancers`, `/admin/freelancers/:id` (UI/UX Spec 6.14)

**Routes:** GET /admin/users → AdminUserController@index; PATCH /admin/users/{user}/status → AdminUserController@updateStatus (Technical Spec Section 5.11).

**Database:** Reads/writes User.status; reads FreelancerProfile joined with User for the Freelancers list.

**Business Rules:**
- Suspending a User immediately blocks login and, for a Freelancer, unpublishes their profile (Recommendation — ties suspension to discovery visibility, consistent with BR-004's spirit).
- Only Admin role can reach these endpoints — enforced server-side (Technical Spec Section 4.4).

**Validation:** status transition must be a recognized value (active, suspended, deactivated).

**Errors:** Non-Admin session → 403.

**Acceptance Criteria:**
- Admin can suspend and reactivate any non-Admin account.
- A suspended Freelancer's profile disappears from discovery immediately.

**Dependencies:** FEATURE-001.

---

## FEATURE-020 — Admin: Category and Skill Approval

**Purpose:** Let Admin manage the fixed taxonomy and review "Other" proposals.
**User:** Admin

**Requirements:** FR-019, FR-020, BR-007

**UI:** `/admin/categories`, `/admin/categories/pending` (UI/UX Spec 6.14)

**Routes:** GET /admin/categories/pending → AdminSkillController@pending; POST /admin/categories/pending/{skill}/approve, /reject → AdminSkillController@approve, @reject (Technical Spec Section 5.11).

**Database:** Updates Skill.status; only Admin can insert/update Category rows directly (no dedicated endpoint listed yet — Recommendation: add basic CRUD for Category management if the taxonomy needs to grow beyond the seeded seven).

**Business Rules:**
- Approving a pending Skill makes it immediately selectable by all freelancers and immediately visible on the submitting freelancer's profile if they had it pending-selected.
- Rejecting notifies the submitting freelancer (FEATURE-015 trigger).
- Near-duplicate proposals can be merged by Admin into one existing approved Skill (Recommendation: implemented as reject-with-reference, rather than a complex merge operation, for v1 simplicity).

**Validation:** N/A beyond standard authorization.

**Errors:** Non-Admin session → 403.

**Acceptance Criteria:**
- A pending skill only becomes usable platform-wide after explicit Admin approval.
- No freelancer can bypass this by re-submitting the same free-text value repeatedly to force adoption.

**Dependencies:** FEATURE-005.

---

## FEATURE-021 — Admin: Reports Queue

**Purpose:** Let Admin review and resolve profile reports.
**User:** Admin

**Requirements:** FR-051

**UI:** `/admin/reports`, `/admin/reports/:id` (UI/UX Spec 6.14)

**Routes:** GET /admin/reports → AdminReportController@index; POST /admin/reports/{report}/resolve → AdminReportController@resolve (Technical Spec Section 5.11).

**Database:** Updates Report.status, resolved_by_admin_id, resolved_at; may cascade into User.status update (warn/suspend) or FreelancerProfile.is_published = false (remove listing).

**Business Rules:**
- Exactly four resolution actions exist: dismiss, warn, suspend, remove listing (FR-051) — no other action types.
- "Warn" notifies the freelancer (FEATURE-015 trigger) but takes no account-level action.
- "Suspend" sets User.status = suspended (same effect as FEATURE-019).
- "Remove listing" sets FreelancerProfile.is_published = false without necessarily suspending the account.

**Validation:** resolution action must be one of the four defined values.

**Errors:** Non-Admin session → 403; invalid action value → 400.

**Acceptance Criteria:**
- Every report resolves to exactly one of the four defined outcomes, recorded with which Admin acted and when.
- "Remove listing" removes the profile from discovery without necessarily suspending the underlying account.

**Dependencies:** FEATURE-016.

---

## Known Open Items Carried Into Implementation

These gaps were identified during earlier analysis and remain unresolved. They do not block building the features above, but should be revisited before or shortly after MVP launch:

- No auto-expiry for requests stuck in Pending Response or Request More Information (affects FEATURE-013).
- No dispute mechanism if a Freelancer contests an Employer's Completed mark (affects FEATURE-013).
- Portfolio and verification-document size/count limits are Recommendations, not yet confirmed (affects FEATURE-006, FEATURE-017).
- Ranking weight formula in FEATURE-018 is a starting proposal, not a specified algorithm — expect to tune after real usage data exists.
