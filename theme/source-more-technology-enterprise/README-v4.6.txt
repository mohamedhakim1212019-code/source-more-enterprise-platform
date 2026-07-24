SOURCE MORE TECHNOLOGY THEME v4.6
=================================

This release separates presentation from business functionality.

THEME CHANGES
- Integrates the companion Source More Platform plugin on the Fleet Savings Calculator page.
- Preserves all v4.5 design, homepage calculator spotlight, bilingual templates and responsive styling.
- Updates asset and theme version to 4.6.0.

COMPANION PLUGIN FEATURES
- Calculator lead capture with consent and spam honeypot.
- Branded downloadable PDF savings report.
- Customer and administrator email notifications through wp_mail.
- Source More CRM in WordPress admin.
- Lead status: New, Contacted, Qualified, Won and Lost.
- CSV export.
- Optional floating MPS assistant.
- Optional secure Cloudflare Worker AI endpoint. API keys must remain in Worker secrets, never WordPress or browser code.

INSTALLATION ORDER
1. Upload and replace the current theme with the v4.6 theme ZIP.
2. Go to Plugins > Add New > Upload Plugin.
3. Upload source-more-platform-v1.0.0.zip and activate it.
4. Go to Source More CRM > Settings.
5. Enter the lead notification email.
6. Enable the MPS assistant if desired.
7. Leave AI endpoint blank for the built-in guided assistant, or enter your Cloudflare Worker URL.
8. Test a calculator submission and verify the PDF, CRM lead and email delivery.

EMAIL NOTE
WordPress wp_mail depends on hosting mail configuration. For reliable delivery, configure an SMTP plugin such as WP Mail SMTP using your business mailbox.
