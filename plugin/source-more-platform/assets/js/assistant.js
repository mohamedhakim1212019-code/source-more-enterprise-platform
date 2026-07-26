document.addEventListener('DOMContentLoaded', () => {
    'use strict';
    if (typeof SMTPAssistant === 'undefined') return;

    const open = document.getElementById('smtp-ai-open');
    const panel = document.getElementById('smtp-ai-panel');
    const close = document.getElementById('smtp-ai-close');
    const form = document.getElementById('smtp-ai-form');
    const input = document.getElementById('smtp-ai-input');
    const messages = document.getElementById('smtp-ai-messages');
    const leadForm = document.getElementById('smtp-ai-lead-form');
    const leadCancel = document.getElementById('smtp-ai-lead-cancel');
    const leadStatus = document.getElementById('smtp-ai-lead-status');
    if (!open || !panel || !form || !input || !messages) return;

    let conversationId = Number(sessionStorage.getItem('smtpAssistantConversationId') || 0);
    let conversationToken = sessionStorage.getItem('smtpAssistantConversationToken') || '';
    const language = SMTPAssistant.language || document.documentElement.lang || 'en';

    const show = () => { panel.hidden = false; open.setAttribute('aria-expanded', 'true'); input.focus(); };
    const hide = () => { panel.hidden = true; open.setAttribute('aria-expanded', 'false'); };
    const add = (text, role) => { const message = document.createElement('div'); message.className = `smtp-ai-msg ${role}`; message.textContent = text; messages.appendChild(message); messages.scrollTop = messages.scrollHeight; };
    const readPayload = async (response) => { const contentType = response.headers.get('content-type') || ''; return contentType.includes('application/json') ? response.json() : {}; };
    const saveIdentity = (payload) => { if (payload.conversation_id && payload.conversation_token) { conversationId = Number(payload.conversation_id); conversationToken = String(payload.conversation_token); sessionStorage.setItem('smtpAssistantConversationId', String(conversationId)); sessionStorage.setItem('smtpAssistantConversationToken', conversationToken); } };

    const showLeadForm = () => { if (!leadForm || !SMTPAssistant.leadCapture) { window.location.href = SMTPAssistant.contact; return; } leadForm.hidden = false; form.hidden = true; leadForm.querySelector('input[name="company"]')?.focus(); };
    const hideLeadForm = () => { if (!leadForm) return; leadForm.hidden = true; form.hidden = false; if (leadStatus) leadStatus.textContent = ''; input.focus(); };
    const runAction = (action) => { if (action === 'calculator') window.location.href = SMTPAssistant.calculator; else if (action === 'products') window.location.href = SMTPAssistant.products; else if (action === 'contact') window.location.href = SMTPAssistant.contact; else if (action === 'lead_capture') showLeadForm(); };

    const ask = async (question, canRetry = true) => {
        add(question, 'user');
        const wait = document.createElement('div'); wait.className = 'smtp-ai-msg bot'; wait.textContent = SMTPAssistant.messages.thinking; messages.appendChild(wait); messages.scrollTop = messages.scrollHeight;
        try {
            const response = await fetch(SMTPAssistant.rest, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': SMTPAssistant.nonce }, body: JSON.stringify({ message: question, language, conversation_id: conversationId, conversation_token: conversationToken }) });
            const payload = await readPayload(response); wait.remove();
            if (!response.ok) {
                if (canRetry && payload.code === 'invalid_conversation') { conversationId = 0; conversationToken = ''; sessionStorage.removeItem('smtpAssistantConversationId'); sessionStorage.removeItem('smtpAssistantConversationToken'); await ask(question, false); return; }
                throw new Error(payload.message || SMTPAssistant.messages.error);
            }
            saveIdentity(payload); add(payload.reply || SMTPAssistant.messages.fallback, 'bot'); if (payload.action) window.setTimeout(() => runAction(payload.action), payload.action === 'lead_capture' ? 250 : 650);
        } catch (error) { wait.remove(); add(error.message || SMTPAssistant.messages.error, 'bot'); }
    };

    open.addEventListener('click', show); close?.addEventListener('click', hide); leadCancel?.addEventListener('click', hideLeadForm);
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !panel.hidden) hide(); });
    document.querySelectorAll('.smtp-ai-quick button').forEach((button) => { button.addEventListener('click', () => { const action = button.dataset.action || ''; if (action === 'calculator') { window.location.href = SMTPAssistant.calculator; return; } if (action === 'lead') { showLeadForm(); return; } if (button.dataset.q) { input.value = button.dataset.q; form.requestSubmit(); } }); });
    form.addEventListener('submit', (event) => { event.preventDefault(); const question = input.value.trim(); if (!question) return; input.value = ''; ask(question); });

    leadForm?.addEventListener('submit', async (event) => {
        event.preventDefault(); const submit = leadForm.querySelector('button[type="submit"]'); const formData = new FormData(leadForm); const payload = Object.fromEntries(formData.entries());
        payload.consent = formData.get('consent') ? 1 : 0; payload.language = leadForm.dataset.language || language; payload.conversation_id = conversationId; payload.conversation_token = conversationToken; payload.source_url = SMTPAssistant.sourceUrl;
        if (leadStatus) leadStatus.textContent = SMTPAssistant.messages.leadSending; if (submit) submit.disabled = true;
        try { const response = await fetch(SMTPAssistant.leadRest, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': SMTPAssistant.nonce }, body: JSON.stringify(payload) }); const result = await readPayload(response); if (!response.ok) throw new Error(result.message || SMTPAssistant.messages.leadError); saveIdentity(result); add(result.message || SMTPAssistant.messages.leadSuccess, 'bot'); leadForm.reset(); hideLeadForm(); }
        catch (error) { if (leadStatus) leadStatus.textContent = error.message || SMTPAssistant.messages.leadError; }
        finally { if (submit) submit.disabled = false; }
    });
});
