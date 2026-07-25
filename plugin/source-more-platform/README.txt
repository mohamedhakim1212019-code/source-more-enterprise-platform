Source More Platform 3.7.1
==========================
Requirements: WordPress 6.4+, PHP 8.0+.

Installation
1. Back up the WordPress database and the existing plugin directory.
2. Upload and activate Source More Platform v3.7.1.
3. Open Source More CRM > Settings and verify the saved module controls.
4. Open Source More CRM > Diagnostics and confirm there are no new boot errors.
5. Save Settings > Permalinks only if routes or product archives do not resolve normally.

Platform architecture
- The bootstrap loads runtime classes in a deterministic order.
- SMTP_Platform registers all default modules in one central registry.
- The registry resolves dependencies and boots enabled modules exactly once.
- Existing module settings remain compatible: CRM, Products, Fleet, AI Assistant, Reports & Analytics, and Diagnostics.
- Product Center is composed from independent content-type, repository, admin, frontend, and REST components.
- CRM is composed from independent content-type, repository, administration, and website contact-capture components.
- Fleet Assessment is composed from independent calculation, frontend, REST, notification, report-access, and PDF components.
- AI Assistant is composed from independent knowledge, endpoint, conversation, REST, frontend, administration, and CRM conversion components.
- Reports & Analytics is composed from independent date-range, repository, aggregation, administration, and export components.

AI Assistant capabilities
- Built-in deterministic knowledge with optional custom Knowledge entries.
- Optional HTTPS external endpoint with automatic built-in fallback.
- Token-protected conversation transcripts and retention cleanup.
- Consent-based callback capture into CRM with notification emails.

Reporting capabilities
- Date-filtered commercial KPIs for Leads and Quote Requests.
- Pipeline, opportunity-source, Fleet Assessment, AI Assistant, and Product Demand analysis.
- Recent-opportunity drill-down and summary CSV export.

CRM capabilities
- Fleet Lead capture is provided by the independent Fleet Assessment Engine.
- Branded PDF reports use expiring token-protected links that also work for logged-out customers.
- Lead stages, source tracking, assignment, last activity, and activity history.
- Product Quote Request integration for CRM status and assignment management.
- Lead and Quote Request dashboard metrics.
- Website Request a Consultation capture with CRM storage and dual email notifications.
- CSV export with CRM ownership and activity fields.

Compatibility
- Keeps the v1 and v2 Fleet Lead REST routes.
- Keeps the v2 and v3 Product Quote Request REST routes.
- Keeps the v1 and v2 AI Assistant reply routes and provides v2/v3 Assistant Lead routes.
- Existing leads, products, quote requests, settings, reports, metadata, and URLs remain compatible.
- SMTP_Leads, SMTP_REST, and SMTP_Products remain available as backward-compatible facades.
- SMTP_Platform::init() remains the supported runtime entry point.
- SMTP_Loader::load_legacy_classes() remains available as a compatibility alias.

Security and resilience
- Duplicate module registration is rejected.
- Missing dependencies and required classes fail safely and are logged.
- Module boot exceptions are contained by the registry.
- REST nonce validation, rate limiting, consent validation, and expiring report tokens remain active.

Important
The optional AI endpoint must be HTTPS and must keep provider API keys server-side.
