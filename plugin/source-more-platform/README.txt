Source More Platform 3.5.0
==========================
Requirements: WordPress 6.4+, PHP 8.0+.

Installation
1. Back up the WordPress database and the existing plugin directory.
2. Upload and activate Source More Platform v3.5.0.
3. Open Source More CRM > Settings and verify the saved module controls.
4. Open Source More CRM > Diagnostics and confirm there are no new boot errors.
5. Save Settings > Permalinks only if routes or product archives do not resolve normally.

Platform architecture
- The bootstrap loads runtime classes in a deterministic order.
- SMTP_Platform registers all default modules in one central registry.
- The registry resolves dependencies and boots enabled modules exactly once.
- Existing module settings remain compatible: CRM, Products, Fleet, AI Assistant, and Diagnostics.
- Product Center is composed from independent content-type, repository, admin, frontend, and REST components.
- CRM is composed from independent content-type, repository, and administration components.
- Fleet Assessment is composed from independent calculation, frontend, REST, notification, report-access, and PDF components.

CRM capabilities
- Fleet Lead capture is provided by the independent Fleet Assessment Engine.
- Branded PDF reports use expiring token-protected links that also work for logged-out customers.
- Lead stages, source tracking, assignment, last activity, and activity history.
- Product Quote Request integration for CRM status and assignment management.
- Lead and Quote Request dashboard metrics.
- CSV export with CRM ownership and activity fields.

Compatibility
- Keeps the v1 and v2 Fleet Lead REST routes.
- Keeps the v2 and v3 Product Quote Request REST routes.
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
