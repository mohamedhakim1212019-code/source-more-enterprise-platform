# Tests

Run the isolated smoke tests from the plugin directory:

```bash
php tests/analytics-date-range-smoke.php
php tests/analytics-module-smoke.php
php tests/analytics-service-smoke.php
php tests/module-registry-smoke.php
php tests/assistant-module-smoke.php
php tests/assistant-knowledge-smoke.php
php tests/assistant-conversations-smoke.php
php tests/contact-capture-smoke.php
php tests/products-module-smoke.php
php tests/crm-module-smoke.php
php tests/crm-repository-smoke.php
php tests/fleet-calculator-smoke.php
php tests/fleet-module-smoke.php
php tests/fleet-report-smoke.php
php tests/platform-boot-smoke.php
php tests/products-quote-hotfix-smoke.php
php tests/products-email-hotfix-smoke.php
```

The Assistant tests verify controlled module boot, REST route compatibility, private content types, deterministic knowledge matching, token verification, conversation transcripts, and CRM lead linkage.

The Fleet tests verify calculation accuracy, bounded assumptions, controlled module boot, public and authenticated report-download hooks, hashed and legacy token validation, expiry enforcement, the v1/v2 REST route contract, and all compatibility facades.

The CRM tests verify CRM data management, assignment, status, source tracking, activity history, exports, Quote Request integration, and website contact-form lead capture.

The Analytics tests verify date-range normalization, controlled module boot, pipeline aggregation, source attribution, Fleet value, AI conversion, Product Demand, and recent-opportunity reporting.

The platform boot test verifies that all default v3.7.0 modules compose successfully and repeated initialization does not duplicate hooks.

WordPress integration testing is still required in LocalWP because these tests intentionally isolate the core architecture.
