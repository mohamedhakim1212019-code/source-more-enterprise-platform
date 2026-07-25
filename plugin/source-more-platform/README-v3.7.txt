Source More Platform v3.7.0 — SMEP Sprint 1.7

Reports & Analytics Module

New module structure:
- includes/modules/analytics/class-smtp-analytics-date-range.php
- includes/modules/analytics/class-smtp-analytics-repository.php
- includes/modules/analytics/class-smtp-analytics-service.php
- includes/modules/analytics/class-smtp-analytics-admin.php
- includes/modules/analytics/class-smtp-analytics-export.php
- includes/modules/analytics/class-smtp-analytics-module.php

New capabilities:
- Reports & Analytics submenu under Source More CRM.
- Last 7, 30, 90, and 365 days, All time, and Custom date periods.
- Consolidated opportunity, open pipeline, Won, Lost, and closed win-rate KPIs.
- Detailed Lead and Quote Request pipelines with CRM drill-down links.
- Opportunity-source performance and conversion analysis.
- Fleet Assessment financial value in EGP.
- AI Assistant conversation and lead-conversion metrics.
- Product Demand aggregation from Quote Requests.
- Recent-opportunity table and summary CSV export.

Compatibility retained:
- Existing CRM Dashboard and pipeline cards.
- Existing Leads, Quote Requests, Products, Fleet reports, AI conversations, emails, metadata, and URLs.
- Existing module settings are extended with Reports & Analytics enabled by default.
- No database migration is required; database version remains 3.1.0.

LocalWP acceptance test:
1. Confirm plugin version 3.7.0.
2. Open Source More CRM > Reports & Analytics.
3. Test 7, 30, 90, 365, All time, and Custom date filters.
4. Confirm Lead and Quote Request counts match the CRM Dashboard for All time.
5. Click pipeline bars and confirm filtered admin lists open.
6. Confirm Fleet values match saved assessment records.
7. Confirm AI metrics match AI Conversations.
8. Confirm Product Demand matches Quote Requests.
9. Export Summary CSV and open it in Excel.
10. Confirm Products, CRM, Fleet Calculator, Contact capture, and AI Assistant still work.
