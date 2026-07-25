Source More Platform v3.6.0 — SMEP Sprint 1.6

AI Assistant Module Refactoring

New module structure:
- includes/modules/assistant/class-smtp-assistant-content-types.php
- includes/modules/assistant/class-smtp-assistant-conversations.php
- includes/modules/assistant/class-smtp-assistant-knowledge.php
- includes/modules/assistant/class-smtp-assistant-endpoint.php
- includes/modules/assistant/class-smtp-assistant-service.php
- includes/modules/assistant/class-smtp-assistant-lead-converter.php
- includes/modules/assistant/class-smtp-assistant-rest.php
- includes/modules/assistant/class-smtp-assistant-frontend.php
- includes/modules/assistant/class-smtp-assistant-admin.php
- includes/modules/assistant/class-smtp-assistant-module.php

New capabilities:
- Manage custom AI Knowledge entries from Source More CRM.
- Retain token-protected AI conversation transcripts with configurable cleanup.
- View AI Conversations and their linked CRM leads.
- Convert assistant visitors into CRM leads through a consent-based callback form.
- Send internal lead notifications and customer confirmations.
- Use the HTTPS external endpoint when configured and the built-in knowledge engine as a fallback.

Compatibility retained:
- Existing Ask Source More widget and frontend IDs.
- source-more/v1/assistant and source-more/v2/assistant REST routes.
- Existing assistant enablement and endpoint settings.
- SMTP_Assistant facade methods.
- Products, Quote Requests, Fleet Calculator, CRM records, reports, and emails.

Database version remains 3.1.0. No database migration is required.
