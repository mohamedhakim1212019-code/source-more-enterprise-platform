(function(){
  'use strict';
  document.addEventListener('submit', async function(event){
    const form = event.target.closest('.smtp-quote-form');
    if (!form) return;
    event.preventDefault();
    const status = form.querySelector('.smtp-quote-status');
    if (!form.reportValidity()) return;
    const button = form.querySelector('button[type="submit"]');
    const data = Object.fromEntries(new FormData(form).entries());
    data.product_id = Number(form.dataset.productId || 0);
    data.quantity = Number(data.quantity || 1);
    data.source_url = window.location.href;
    status.textContent = SMTPProducts.sending;
    status.className = 'smtp-quote-status is-loading';
    button.disabled = true;
    try {
      const response = await fetch(SMTPProducts.rest, {method:'POST',headers:{'Content-Type':'application/json','X-WP-Nonce':SMTPProducts.nonce},body:JSON.stringify(data)});
      const contentType = response.headers.get('content-type') || '';
      const payload = contentType.includes('application/json') ? await response.json() : {};
      if (!response.ok) throw new Error(payload.message || SMTPProducts.error);
      status.textContent = SMTPProducts.success;
      status.className = 'smtp-quote-status is-success';
      form.reset();
    } catch(error) {
      status.textContent = error.message || SMTPProducts.error;
      status.className = 'smtp-quote-status is-error';
    } finally { button.disabled = false; }
  });
})();
