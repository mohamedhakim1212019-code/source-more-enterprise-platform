Source More Technology Enterprise Theme — v7.2.0
=================================================

Sprint 1.1: Header & Navigation Stabilization

Implemented:
- Corrected invalid nested anchor markup around the WordPress custom logo.
- Constrained logo dimensions to prevent header overlap.
- Added stable sticky-header and scroll-shadow behavior.
- Added accessible desktop dropdown navigation for standard WordPress submenus.
- Added mobile drawer navigation, backdrop, animated menu button, and body scroll lock.
- Added keyboard Escape handling, submenu buttons, ARIA state updates, and skip link.
- Added active-page and current-parent navigation states.
- Added responsive sizing for desktop, tablet, and mobile.
- Preserved the Products page in the primary navigation.
- Added solution pages beneath Solutions in the theme-generated editable WordPress menu.
- Updated theme asset and package version to 7.2.0.

Testing checklist:
1. Activate the theme on LocalWP.
2. Clear browser/cache-plugin caches.
3. Open Appearance > Menus and confirm Products remains visible.
4. Confirm Solutions has child pages in the generated Source More Primary Menu.
5. Test desktop hover, keyboard Tab navigation, and submenu buttons.
6. Test the mobile menu at widths below 1100px.
7. Test English and Arabic layouts.
8. Confirm the logo does not overlap the navigation at 100%, 125%, and 150% browser zoom.
