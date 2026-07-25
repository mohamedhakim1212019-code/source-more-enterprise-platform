Source More Platform v3.3.0
===========================
Sprint 1.3 converts the Product Center from one legacy class into the first independently composed SMEP business module.

Product module components
- SMTP_Products_Module: composition root and one-time module boot.
- SMTP_Products_Content_Types: products, quote requests, categories, and brands.
- SMTP_Products_Repository: product queries, card data, and quote-request persistence.
- SMTP_Products_Admin: meta boxes, save handlers, and admin list columns.
- SMTP_Products_Frontend: assets, Product Center shortcode, and quote form.
- SMTP_Products_REST: v2/v3 quote-request routes, validation, rate limiting, email, and response handling.
- SMTP_Products: backward-compatible facade for existing integrations.

Compatibility retained
- Product post type: smt_product.
- Quote request post type: smt_quote_request.
- Taxonomies: smt_product_category and smt_product_brand.
- Product archive/rewrite: /products/.
- Shortcodes: [smtp_products] and [source_more_quote_form].
- REST routes: source-more/v2/quote-request and source-more/v3/quote-request.
- Existing metadata, products, quote requests, theme templates, and frontend assets.

Versioning
- Plugin version: 3.3.0
- Database version: 3.1.0 (unchanged; no migration required)
