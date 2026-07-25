Source More Platform 3.3.0
==========================
Requirements: WordPress 6.4+, PHP 8.0+.

Installation
1. Back up the WordPress database and the existing plugin directory.
2. Upload and activate Source More Platform v3.3.0.
3. Open Source More CRM > Settings and verify the saved module controls.
4. Open Source More CRM > Diagnostics and confirm there are no new boot errors.
5. Save Settings > Permalinks only if routes or product archives do not resolve normally.

Platform architecture
- The bootstrap loads the runtime classes in a deterministic order.
- SMTP_Platform registers all default modules in one central registry.
- The registry resolves dependencies and boots enabled modules exactly once.
- Existing module settings remain compatible: CRM, Products, Fleet, AI Assistant, and Diagnostics.
- Product Center is now composed from independent content-type, repository, admin, frontend, and REST components.

Compatibility
- Keeps the v1 REST routes for older theme integrations.
- Keeps the v2 REST routes for current integrations.
- Existing leads, products, quote requests, settings, and reports are unchanged.
- SMTP_Products remains available as a backward-compatible facade.
- SMTP_Platform::init() remains the supported runtime entry point.
- SMTP_Loader::load_legacy_classes() remains available as a compatibility alias.

Security and resilience
- Duplicate module registration is rejected.
- Missing dependencies and required classes fail safely and are logged.
- Module boot exceptions are contained by the registry.
- REST nonce validation, rate limiting, and expiring report tokens remain active.

Important
The optional AI endpoint must be HTTPS and must keep provider API keys server-side.
