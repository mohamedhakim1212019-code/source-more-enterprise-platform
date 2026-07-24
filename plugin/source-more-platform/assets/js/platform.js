document.addEventListener('DOMContentLoaded',()=>{
 const leadForm=document.getElementById('smtp-lead-form'); if(!leadForm||typeof SMTPPlatform==='undefined')return;
 const id=x=>document.getElementById(x); const value=x=>Number(id(x)?.value||0);
 leadForm.addEventListener('submit',async e=>{
  e.preventDefault(); const status=id('smtp-form-status'),button=leadForm.querySelector('button[type=submit]');
  if(button.disabled||!leadForm.reportValidity())return; status.textContent=SMTPPlatform.messages.sending; button.disabled=true;
  const fd=new FormData(leadForm),payload=Object.fromEntries(fd.entries());
  Object.assign(payload,{devices:value('calc-devices'),mono_pages:value('calc-mono-pages'),color_pages:value('calc-color-pages'),mono_cpp:value('calc-mono-cpp'),color_cpp:value('calc-color-cpp'),fixed_cost:value('calc-fixed-cost'),saving_rate:value('calc-saving-rate'),consent:fd.get('consent')?1:0});
  try{const r=await fetch(SMTPPlatform.rest,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-WP-Nonce':SMTPPlatform.nonce},body:JSON.stringify(payload)});const j=await r.json();if(!r.ok||!j.success)throw new Error(j.message||SMTPPlatform.messages.error);const link=id('smtp-download-report');if(link)link.href=j.report_url;leadForm.hidden=true;const success=id('smtp-success');success.hidden=false;status.textContent='';success.scrollIntoView({behavior:'smooth',block:'center'});}catch(err){status.textContent=err.message||SMTPPlatform.messages.error;}finally{button.disabled=false;}
 });
});
