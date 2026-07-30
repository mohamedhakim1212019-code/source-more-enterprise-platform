# Changelog

## 3.9.0
- Introduced secure branded report portal URLs at `/report/{lead}/{token}/`.
- Upgraded the fleet report to a three-page enterprise assessment.
- Added executive KPI cards, analytics dashboard, cost comparison, and five-year projection.
- Added optimization opportunity matrix and current-environment analysis.
- Added indicative environmental impact metrics with clear planning disclaimers.
- Added engagement roadmap, consultation CTAs, WhatsApp action, and print/PDF mode.
- Retained legacy report links for backward compatibility.

## 3.8.2
- Refined the Fleet Savings report header for a more compact executive layout.
- Uses the complete Source More website logo lockup when the enterprise theme is active.
- Added a branded footer with phone, email, address, website, and report number.
- Footer contact details are loaded automatically from Source More theme settings.
- Reduced print spacing to prevent an unnecessary footer-only final page.

# Changelog

## 3.8.1
- Replaced the legacy ASCII-only Fleet PDF renderer with a print-optimized bilingual HTML report.
- Added full Arabic report translation and native RTL layout while preserving English LTR output.
- Added the active website custom logo, Navy/Gold Source More branding, executive KPI cards, cost comparison, environment details, and recommendations.
- Added report number, issue date, responsive presentation, and A4 print styling for browser Save as PDF.
- Localized invalid or expired report-link messages.
- Preserved existing secure report URLs, lead metadata, REST endpoints, and database version 3.1.0.

## 3.8.0

- Added a shared English/Arabic public-experience layer with Polylang-aware language and URL resolution.
- Made Product Center products, categories, brands, archive labels, product detail labels, filters, quotation forms, validation messages, and customer emails bilingual.
- Added Arabic Product Quote Request confirmations while preserving English administration notifications and CRM records.
- Made Fleet Assessment lead capture, status messages, validation, customer confirmation emails, and language tracking bilingual.
- Made the AI Assistant launcher, panel, quick actions, question form, callback form, built-in knowledge answers, validation, links, and customer emails bilingual.
- Added Arabic deterministic AI knowledge for Managed Print Services, Fleet Savings, Cloud, Cybersecurity, Document Management, Products, and Contact requests.
- Added Polylang support for Products, Product Categories, Brands, and AI Knowledge entries while keeping CRM records language-neutral.
- Preserved the existing REST namespaces, post types, metadata, CRM integrations, PDF report access, and database version 3.1.0.

## 3.7.1

- Added a branded, multi-page PDF export alongside the existing Analytics CSV export.
- Added printable Executive Summary, Lead and Quote Request pipelines, opportunity sources, Fleet value, AI performance, Product Demand, and Recent Opportunities sections.
- Added page headers, page numbers, EGP financial formatting, and automatic table pagination without external PDF libraries.
- Kept the database version at 3.1.0 because this release requires no schema migration.

## 3.7.0

- Added an independent Reports & Analytics module with dedicated date-range, repository, aggregation, administration, and CSV export services.
- Added preset and custom reporting periods without changing the database schema.
- Added consolidated commercial KPIs across CRM Leads and Product Quote Requests, including open pipeline, Won, Lost, and closed win rate.
- Added clickable Lead and Quote Request pipeline analysis linked to the existing filtered CRM lists.
- Added opportunity-source performance for Fleet Calculator, AI Assistant, Website Contact, Product Quote Form, and Manual records.
- Added Fleet Assessment value metrics in EGP, including assessed devices, current annual cost, annual savings, three-year savings, and average optimization rate.
- Added AI Assistant conversation, message, lead-conversion, and average-message metrics.
- Added Product Demand aggregation from Quote Requests and a recent-opportunities drill-down table.
- Added a privacy-safe summary CSV export and a new Reports & Analytics module control.
- Kept the database version at 3.1.0 because this release reads existing WordPress posts and metadata and requires no schema migration.

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
