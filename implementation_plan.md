# Redesign Dashboard UI & Layout Partials for Premium Cockpit Aesthetic

Redesign and polish the user interfaces of the PPKD Hotel Management System (HMS), including the core layouts, dashboards, and partial views, to match the high-quality, modern design aesthetic required by the `design-taste-frontend` skill. 

The focus will be on eliminating hardcoded color classes (like `bg-white`, `bg-light`, `text-dark`) that break dark mode contrast, styling high-quality soft badges, formatting details cleanly, verifying shape and typography consistency, and adding smooth micro-animations to layout elements.

## User Review Required

> [!IMPORTANT]
> The primary change is replacing default Bootstrap classes with tailored CSS styles that adapt dynamically to light/dark themes.
> We are adding specific premium styling updates to the layout partials (`navbar`, `sidebar`, and `footer`) to enhance readability, contrast, and visual appeal.
> No database structure or server controller logic will be changed, preserving existing functionality.

## Proposed Changes

### Styling & Theming System

#### [MODIFY] [style.css](file:///c:/xampp/htdocs/hotel-project/public/template/assets/css/style.css)
- Implement custom utility classes for **soft tinted badges** (`.badge-soft-primary`, `.badge-soft-success`, `.badge-soft-warning`, `.badge-soft-danger`, `.badge-soft-info`, `.badge-soft-secondary`) with customized border-radius and letter-spacing for premium feel.
- Style modern scrollbars and focus rings.
- Ensure form inputs, tables, and buttons have consistent corner radius scaling (`border-radius: 12px` or similar soft corners).
- Provide helper panels (`.panel-soft`, `.panel-flat`) that adapt to `--admin-surface-soft` or have slight border variations instead of using hardcoded Bootstrap backgrounds.
- Add transition animations for theme toggling and sidebar link hover states.

---

### Layout Partials

#### [MODIFY] [navbar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/navbar.blade.php)
- Clean up hardcoded `.bg-white` behavior, relying instead on `.admin-navbar`.
- Polish user profile dropdown to render as a sleek, modern card.
- Add visual indicators for the active theme state.

#### [MODIFY] [sidebar.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/sidebar.blade.php)
- Upgrade link styling: active states should use high-contrast primary accents, and hover states should have smooth horizontal translation and scale feedback.
- Clean up role header dividers (`Master Data`, `Front Office`, etc.) to use modern uppercase subheadings with refined letter-spacing.
- Redesign the bottom profile badge/user widget to blend beautifully with the sidebar background.

#### [MODIFY] [footer.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/layouts/partials/footer.blade.php)
- Polish the footer layout with low-opacity text, clean dividers, and a balanced layout.

---

### Dashboard Cleanups

#### [MODIFY] [admin.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/dashboards/admin.blade.php)
- Clean up any remaining hardcoded container backgrounds or text colors.
- Enhance room grid status visualization using soft borders and distinct indicators.

#### [MODIFY] [fo.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/dashboards/fo.blade.php)
- Upgrade the interactive Room Map card shapes and pricing formats.
- Fix any `badge bg-light` usages to use theme-aware soft badges.

#### [MODIFY] [fb.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/dashboards/fb.blade.php)
- Polish the F&B room service active order list layout.
- Use soft badges for order statuses (`Preparing`, `Ready`, `Delivered`).

---

### Housekeeping Consolidated Hub

#### [MODIFY] [hub.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/housekeeping/hub.blade.php)
- Replace all instances of `bg-white` and `bg-light` with theme-variable compatible styles (`.panel`, `.panel-soft`, `.mini-card`).
- Standardize the active tabs component to style beautifully on mobile viewports.
- Replace basic check-in/checkout badge types with soft badges.
- Fix hardcoded background colors in the damage form section.

---

### Reservations Folio & POS Cart

#### [MODIFY] [show.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/reservations/show.blade.php)
- Style the Extra Bed form and F&B Room Service POS Cart to fit a clean, cohesive container with visible border guidelines.
- Remove hardcoded `bg-light` on checkout section.
- Polish text colors, inputs, table rows, and borders.

---

### Master Data & Forms

#### [MODIFY] [create.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/master/users/create.blade.php)
- Remove inline `background-color` styles on error alert blocks.
- Clean up inputs and make button corner shapes consistent.

#### [MODIFY] [edit.blade.php](file:///c:/xampp/htdocs/hotel-project/resources/views/master/users/edit.blade.php)
- Remove inline `background-color` styles on error alert blocks.
- Polish edit profile form inputs and save button actions.

---

## Verification Plan

### Manual Verification
- Log in with various roles (`Admin`, `FO`, `HK`, `FB`) to view dashboards and verify visual elements.
- Toggle between **Light Mode** and **Dark Mode** on each dashboard, Housekeeping Hub, and Guest Folio page to inspect contrast levels and check for any unreadable text.
- Check validation messages on Create/Edit staff user form to ensure errors read clearly.
- Validate F&B POS order submission.
