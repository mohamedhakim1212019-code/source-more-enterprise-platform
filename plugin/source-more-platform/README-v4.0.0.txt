Source More Enterprise Platform (SMEP) v4.0.0
================================================

Phase 1 foundation release.

New foundation capabilities:
- SMEP Core Framework registered as the first platform module.
- Lightweight service container for reusable platform services.
- Role-based access control foundation with Administrator, Sales Manager,
  Sales Executive, and Customer roles.
- Persistent audit log database table for important platform events.
- Versioned REST API namespace: /wp-json/sourcemore/v1/platform/status
- Enterprise design system CSS tokens and reusable administration components.
- New Platform Architecture administration page showing module health,
  services, API status, and recent audit events.
- Existing CRM, Fleet, Reports, Products, Analytics, and AI modules remain
  compatible with the v3.9 report portal.

Upgrade notes:
1. Upload and replace the existing Source More Platform plugin.
2. Reactivate only if WordPress requests it.
3. Open Source More CRM > Platform Architecture to verify the foundation.
4. Existing reports and report portal links remain available.
