Source More Platform v3.3.1
===========================
Sprint 1.3.1 is a targeted hotfix for Product Quote Requests.

Fixes
- Restores quote-request submission by adding the SMTP_Rate_Limiter::allow() compatibility method.
- Uses a stable RFQ rate-limit bucket while IP isolation remains inside the rate limiter.
- Prevents raw HTML error pages from being displayed in the quote form status area.

Compatibility
- No database changes.
- No post type, taxonomy, shortcode, REST route, metadata, theme, or design changes.
- Product and quote-request data remain unchanged.

Versioning
- Plugin version: 3.3.1
- Database version: 3.1.0
