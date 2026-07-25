Source More Platform v3.7.1 - Analytics PDF Export

Added capabilities:
- Download PDF Report button beside Export Summary CSV.
- Branded A4 multi-page report suitable for printing and sharing.
- Executive Summary, Lead Pipeline, Quote Request Pipeline, Opportunity Sources, Fleet Assessment Value, AI Assistant Performance, Product Demand, and Recent Opportunities.
- Automatic page breaks, repeated table headers, page numbering, and EGP financial formatting.

Compatibility:
- Existing CSV export remains unchanged.
- The selected reporting period is applied to both CSV and PDF exports.
- No database migration is required; database version remains 3.1.0.

LocalWP acceptance test:
1. Confirm plugin version 3.7.1.
2. Open Source More CRM > Reports & Analytics.
3. Select All time and download both CSV and PDF.
4. Confirm the PDF opens, prints, and includes the selected period.
5. Test a custom date period and confirm both exports use the same dates.
6. Confirm no Analytics, CRM, Fleet, Products, or AI regression.
