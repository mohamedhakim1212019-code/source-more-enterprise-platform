Source More Platform v3.5.0 — SMEP Sprint 1.5

Fleet Assessment Module Refactoring

New module structure:
- includes/modules/fleet/class-smtp-fleet-calculator.php
- includes/modules/fleet/class-smtp-fleet-pdf.php
- includes/modules/fleet/class-smtp-fleet-report.php
- includes/modules/fleet/class-smtp-fleet-notifications.php
- includes/modules/fleet/class-smtp-fleet-frontend.php
- includes/modules/fleet/class-smtp-fleet-rest.php
- includes/modules/fleet/class-smtp-fleet-module.php

Compatibility retained:
- Fleet Savings Calculator page and UI
- [smt_fleet_lead_form] shortcode
- source-more/v1/lead and source-more/v2/lead REST routes
- smt_fleet_lead post type and existing metadata
- CRM pipeline, activity history, CSV export, emails, and PDF reports
- Existing compatibility classes and public methods

Functional improvement:
- Token-protected PDF report links now work for logged-out customers through admin_post_nopriv.

Database version remains 3.1.0. No database migration is required.
