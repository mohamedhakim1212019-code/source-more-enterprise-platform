Source More Platform v3.4.0

SMEP Foundation — Sprint 1.4
CRM & Leads Module Refactoring

Changes:
- Refactored Fleet Leads into an independent CRM module composition root.
- Split CRM content types, repository, admin, frontend, REST, and notification responsibilities.
- Preserved the existing Fleet Lead post type, shortcode, REST endpoints, PDF reports, email notifications, metadata, and exports.
- Added controlled lead assignment, source tracking, status filters, last activity, and activity history.
- Integrated Product Quote Requests into CRM assignment, status, and activity management without moving Product Center data.
- Added Quote Request metrics and navigation to the CRM dashboard.
- Retained SMTP_Leads and SMTP_REST as backward-compatible facades.
- No database schema migration is required; new CRM values are stored as standard WordPress post metadata.

LocalWP validation:
1. Replace the existing plugin with v3.4.0.
2. Confirm Dashboard, Products, Quote Requests, Fleet Calculator, Leads, PDF, email, and AI Assistant still work.
3. Open an existing lead and verify CRM Management, assignment, status, source, and activity history.
4. Change a lead status and assignment, save, and verify the activity timeline.
5. Open a Quote Request and perform the same status and assignment test.
6. Export Leads and verify the new Source, Assigned To, and Last Activity columns.
