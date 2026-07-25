# Changelog

## 3.6.1

- Integrated the enterprise theme Request a Consultation form with Source More CRM.
- Created Website Contact leads with status, source, consent, area of interest, message, and activity history.
- Added internal notification and customer confirmation emails without discarding leads when mail delivery fails.
- Added Website Contact to CRM source filters and exported contact inquiry fields in CSV.
- Preserved the existing `smt_contact_submit` AJAX action, nonce, frontend form, and response format.
- Kept the database version at 3.1.0 because this release uses WordPress post metadata and requires no schema migration.


## 3.6.0

- Refactored the AI Assistant into an independent SMEP module with dedicated content types, knowledge retrieval, endpoint adapter, conversation repository, REST delivery, frontend, administration, and CRM lead conversion services.
- Added private AI Knowledge entries that administrators can manage from the Source More CRM menu using keywords, priority, enablement, and optional visitor actions.
- Added token-protected conversation histories with capped transcripts, message source tracking, converted status, linked CRM leads, and configurable retention cleanup.
- Added a visitor callback form inside the assistant that creates CRM leads with the `ai-assistant` source and sends administration and customer confirmation emails.
- Added AI Conversations and AI Knowledge dashboard shortcuts and corrected assistant health status to reflect the built-in knowledge fallback.
- Preserved `source-more/v1/assistant` and `source-more/v2/assistant`, and added versioned assistant lead-capture routes.
- Retained `SMTP_Assistant` as a backward-compatible facade and kept all existing assistant settings and frontend IDs compatible.
- Added isolated Assistant module, knowledge, conversation, and full-platform boot smoke tests.
- Kept the database version at 3.1.0 because WordPress posts, post metadata, options, and cron are used without schema migration.

## 3.5.0

- Refactored Fleet Savings Calculator into an independent Fleet Assessment module.
- Separated calculation, frontend capture, REST delivery, notifications, report access, and PDF rendering into dedicated services.
- Kept the existing calculator page, shortcode, lead metadata, REST v1/v2 routes, CRM records, emails, and report URLs compatible.
- Added public token-protected report downloads for customers who are not logged in to WordPress.
- Added calculation filters and a `smtp_fleet_assessment_created` integration event.
- Retained `SMTP_Leads`, `SMTP_REST`, `SMTP_CRM_REST`, `SMTP_CRM_Frontend`, `SMTP_CRM_Notifications`, and `SMTP_Simple_PDF` as compatibility facades.
- Kept the database version at 3.1.0 because no schema migration is required.

## 3.4.1
- Split the CRM dashboard into complete Lead Pipeline and Quote Request Pipeline sections.
- Added Total, New, Contacted, Qualified, Proposal, Won, and Lost metrics for both record types.
- Made every dashboard metric clickable and linked it to the corresponding filtered admin list.
- Treated legacy leads and quote requests without stored CRM status as New in dashboard counts and admin filters.
- Kept the database version at 3.1.0 because this release requires no schema migration.

## 3.4.0
- Refactored Fleet Leads and CRM into an independent SMEP module composition root.
- Split CRM content types, repository, administration, frontend, REST, and notification responsibilities into dedicated classes.
- Retained `SMTP_Leads` and `SMTP_REST` as backward-compatible facades with the original post type, shortcode, routes, reports, and public methods.
- Added controlled assignment, source tracking, status filtering, last-activity tracking, and a capped activity history for leads.
- Integrated Product Quote Requests with CRM status, assignment, source, and activity management while preserving Product Center ownership of quote data.
- Added Quote Request metrics and navigation to the CRM dashboard.
- Expanded CSV exports with Source, Assigned To, and Last Activity fields.
- Added CRM module smoke testing and updated the full platform boot test.
- Kept the database version at 3.1.0 because Sprint 1.4 uses WordPress post metadata and requires no schema migration.

## 3.3.2
- Fixed Product Quote notifications to use the configured `notification_email` setting.
- Added a confirmation email to the customer who submits the quote request.
- Added delivery-status logging for both internal and customer emails.
- Preserved successful quote capture when mail delivery is unavailable.

## 3.3.1
- Fixed a fatal error when submitting Product Quote Requests.
- Added `SMTP_Rate_Limiter::allow()` as a compatibility alias.
- Hardened quote-form handling for non-JSON server errors.

## 3.3.0
- Refactored Product Center into an independent SMEP module composition root.
- Split product content types, repository, admin, frontend, and REST responsibilities into dedicated classes.
- Updated the central platform registry to boot `SMTP_Products_Module` directly.
- Retained `SMTP_Products` as a backward-compatible facade, including all legacy constants and public methods.
- Preserved post-type keys, taxonomies, archive and rewrite slugs, shortcodes, REST namespaces, metadata keys, admin menu placement, and frontend assets.
- Added Product Center smoke testing for hook-contract parity, idempotent boot, content-type compatibility, and facade compatibility.
- Kept the database version at 3.1.0 because Sprint 1.3 introduces no schema or content migration.

## 3.2.0
- Added the central `SMTP_Module_Registry` for controlled module registration and initialization.
- Added the immutable `SMTP_Module` runtime definition with dependencies, enablement callbacks, and required-class checks.
- Registered Settings, Dashboard, Leads, Products, REST API, AI Assistant, and Diagnostics through one platform entry point.
- Added duplicate-registration protection, dependency resolution, module status tracking, safe failure logging, and one-time boot guarantees.
- Preserved existing module settings, REST routes, data models, shortcodes, admin pages, and public behavior.
- Added a standalone module-registry smoke test.
- Kept the database version at 3.1.0 because Sprint 1.2 introduces no schema or content migration.

## 2.0.0
- Added v2 REST API while preserving v1 compatibility.
- Added rate limiting for lead and assistant endpoints.
- Added hashed and expiring PDF report tokens.
- Added operational logging and diagnostics.
- Added platform bootstrap and upgrade handling.
- Hardened admin actions, validation, email failure logging, and PDF output.
- Added lead proposal stage, last activity, and consent timestamp.
- Improved accessibility and resilience of assistant and lead capture scripts.

## 2.1.0
- Added CRM dashboard with lead-stage metrics and system status.
- Reorganized admin navigation under Source More CRM.
- Added real email-delivery test from Platform Settings.
- Improved theme integration readiness and operational visibility.

## 2.2.0
- Added Product Center data model with Products, Categories, and Brands.
- Added hardware, software, service/subscription, availability, pricing mode, specifications, and brochure fields.
- Added Quote Requests with secure REST submission, rate limiting, email notification, and admin management.
- Added reusable product quote form shortcode and frontend assets.

## 3.1.0
- Added modular feature controls.
- Added Product Center shortcode and responsive product grid.
- Improved active-version upgrade handling.
