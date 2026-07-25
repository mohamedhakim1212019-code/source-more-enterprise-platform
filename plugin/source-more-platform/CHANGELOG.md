# Changelog

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
