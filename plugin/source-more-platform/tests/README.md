# Tests

Run the isolated smoke tests from the plugin directory:

```bash
php tests/module-registry-smoke.php
php tests/products-module-smoke.php
php tests/crm-module-smoke.php
php tests/crm-repository-smoke.php
php tests/platform-boot-smoke.php
php tests/products-quote-hotfix-smoke.php
php tests/products-email-hotfix-smoke.php
```

The registry test verifies deterministic dependency boot, duplicate prevention, disabled-module handling, requirement failures, and idempotent initialization.

The Product Center test verifies the existing Product and Quote Request contracts, post types, taxonomies, shortcodes, REST routes, and backward-compatible facade.

The CRM test verifies:

- controlled one-time CRM module boot;
- the original `smt_fleet_lead` post-type key and admin placement;
- the existing `smt_fleet_lead_form` shortcode;
- both v1 and v2 lead REST endpoints;
- assignment, status, source, activity, quote-request integration hooks;
- backward compatibility through `SMTP_Leads` and `SMTP_REST`.

The CRM repository test verifies lead and Quote Request status, assignment, source, last-activity, and activity-history metadata behavior.

The platform boot test verifies that all default v3.4.0 modules compose successfully and repeated initialization does not duplicate hooks.

WordPress integration testing is still required in LocalWP because these tests intentionally isolate the core architecture.
