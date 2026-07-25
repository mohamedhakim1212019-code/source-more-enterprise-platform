Source More Platform v3.2.0
===========================
Sprint 1.2 introduces the controlled Module Registry without changing the database schema or public website behavior.

Core additions
- SMTP_Module: immutable module definition and runtime contract.
- SMTP_Module_Registry: central registration, dependency resolution, status tracking, and one-time boot control.
- Default runtime modules registered by SMTP_Platform: Settings, Dashboard, Leads, Products, REST API, AI Assistant, and Diagnostics.
- Backward-compatible SMTP_Platform::init() and SMTP_Loader::load_legacy_classes() entry points.
- Standalone registry smoke test.

Versioning
- Plugin version: 3.2.0
- Database version: 3.1.0 (unchanged; no migration required)
