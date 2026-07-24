Source More Platform 2.0.0
==========================
Requirements: WordPress 6.4+, PHP 8.0+.

Installation
1. Upload and activate the plugin.
2. Open Source More CRM > Settings.
3. Set the lead notification email and privacy URL.
4. Install/configure an SMTP plugin for reliable email delivery.
5. Save Settings > Permalinks once.
6. Open Source More CRM > Diagnostics.

Compatibility
- Keeps the v1 REST routes for Theme v4.6/v5 compatibility.
- Adds v2 REST routes for Theme v6.1+.
- Existing v1 lead records remain available.

Security
- REST nonce validation
- Honeypot and server-side validation
- Per-IP rate limits
- Hashed, expiring report tokens for new reports
- Capability and nonce checks for administrative actions

Important
The optional AI endpoint must be HTTPS and must keep provider API keys server-side.
