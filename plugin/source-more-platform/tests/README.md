# Tests

Run the isolated smoke tests from the plugin directory:

```bash
php tests/module-registry-smoke.php
php tests/products-module-smoke.php
php tests/platform-boot-smoke.php
```

The registry test verifies:

- deterministic dependency boot order;
- duplicate-registration prevention;
- disabled-module skipping;
- missing dependency and runtime requirement failures;
- idempotent initialization.

The Product Center test verifies:

- the legacy twelve-hook contract remains unchanged;
- repeated Product module and facade initialization do not duplicate hooks;
- the original post-type and taxonomy keys remain intact;
- archive, rewrite, and admin-menu contracts remain compatible;
- the two existing shortcode tags remain registered;
- the `SMTP_Products` compatibility facade remains available.

The platform boot test verifies:

- all default v3.3.0 modules can be registered and booted together;
- the refactored Product Center module composes successfully with the existing runtime;
- repeated `SMTP_Platform::init()` calls do not duplicate hooks.

WordPress integration testing is still required in LocalWP because these tests intentionally isolate the core architecture.
