# Tests

Run the isolated smoke tests from the plugin directory:

```bash
php tests/module-registry-smoke.php
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

The Fleet tests verify calculation accuracy, bounded assumptions, controlled module boot, public and authenticated report-download hooks, hashed and legacy token validation, expiry enforcement, the v1/v2 REST route contract, and all compatibility facades.

The CRM test verifies that CRM data management, assignment, status, source tracking, activity history, exports, and Quote Request integration remain separate from Fleet delivery concerns.

The platform boot test verifies that all default v3.5.0 modules compose successfully and repeated initialization does not duplicate hooks.

WordPress integration testing is still required in LocalWP because these tests intentionally isolate the core architecture.
