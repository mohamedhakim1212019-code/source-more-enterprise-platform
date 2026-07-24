document.addEventListener('DOMContentLoaded',()=>{
 const toggle=document.querySelector('.menu-toggle'),nav=document.querySelector('.primary-nav'),header=document.getElementById('site-header');
 if(toggle&&nav) toggle.addEventListener('click',()=>{const open=nav.classList.toggle('is-open');toggle.setAttribute('aria-expanded',String(open));document.body.classList.toggle('nav-open',open)});
 document.querySelectorAll('.nav-parent').forEach(btn=>btn.addEventListener('click',e=>{if(innerWidth<=1100){e.preventDefault();const item=btn.closest('.has-mega-menu'),open=item.classList.toggle('is-open');btn.setAttribute('aria-expanded',String(open));}}));
 document.addEventListener('click',e=>{if(innerWidth>1100&&!e.target.closest('.has-mega-menu'))document.querySelectorAll('.nav-parent').forEach(b=>b.setAttribute('aria-expanded','false'));});
 addEventListener('scroll',()=>header&&header.classList.toggle('is-scrolled',scrollY>12),{passive:true});
 const io='IntersectionObserver'in window?new IntersectionObserver(es=>es.forEach(e=>e.isIntersecting&&e.target.classList.add('is-visible')),{threshold:.12}):null;document.querySelectorAll('.reveal').forEach(x=>io?io.observe(x):x.classList.add('is-visible'));
 document.querySelectorAll('[data-accordion]').forEach(a=>a.querySelectorAll('.ui-faq-item button').forEach(b=>b.addEventListener('click',()=>{const ex=b.getAttribute('aria-expanded')==='true',answer=b.closest('.ui-faq-item')?.querySelector('.ui-faq-answer');b.setAttribute('aria-expanded',String(!ex));if(answer)answer.hidden=ex;})));
});
// v6.0 Fleet Savings Calculator
(() => {
 const form=document.getElementById('fleet-calculator');
 if(!form) return;
 const id=x=>document.getElementById(x);
 const val=x=>Math.max(0,Number(id(x)?.value)||0);
 const currency=n=>new Intl.NumberFormat(document.documentElement.lang==='ar'?'ar-EG':'en-EG',{style:'currency',currency:'EGP',maximumFractionDigits:0}).format(n);
 const integer=n=>new Intl.NumberFormat(document.documentElement.lang==='ar'?'ar-EG':'en-EG',{maximumFractionDigits:0}).format(n);
 const calculate=()=>{
   const devices=Math.max(1,val('calc-devices'));
   const mono=val('calc-mono-pages'),color=val('calc-color-pages');
   const monoCpp=val('calc-mono-cpp'),colorCpp=val('calc-color-cpp'),fixed=val('calc-fixed-cost');
   const rate=val('calc-saving-rate')/100;
   const annualPages=(mono+color)*12;
   const annualPageCost=((mono*monoCpp)+(color*colorCpp))*12;
   const annualFixed=fixed*12;
   const current=annualPageCost+annualFixed;
   const savings=current*rate;
   const optimized=current-savings;
   id('result-annual-savings').textContent=currency(savings);
   id('result-current-cost').textContent=currency(current);
   id('result-optimized-cost').textContent=currency(optimized);
   id('result-three-year').textContent=currency(savings*3);
   id('result-annual-pages').textContent=integer(annualPages);
   id('result-page-cost').textContent=currency(annualPageCost);
   id('result-fixed-cost').textContent=currency(annualFixed);
   id('result-device-cost').textContent=currency(current/devices);
 };
 const range=id('calc-saving-rate'),output=id('calc-rate-output');
 const updateRate=()=>{if(output&&range)output.textContent=`${range.value}%`;calculate();};
 form.addEventListener('submit',e=>{e.preventDefault();calculate();document.querySelector('.calculator-results-panel')?.scrollIntoView({behavior:'smooth',block:'nearest'});});
 form.querySelectorAll('input').forEach(input=>input.addEventListener('input',input===range?updateRate:calculate));
 updateRate();
})();

// v6.0: accessibility and AI assistant bridge
(() => {
 const toggle=document.querySelector('.menu-toggle');
 const nav=document.querySelector('.primary-nav');
 const closeNav=()=>{if(!toggle||!nav)return;nav.classList.remove('is-open');toggle.setAttribute('aria-expanded','false');document.body.classList.remove('nav-open');};
 document.addEventListener('keydown',event=>{if(event.key==='Escape')closeNav();});
 document.querySelectorAll('.primary-nav a').forEach(link=>link.addEventListener('click',()=>{if(innerWidth<=1100)closeNav();}));
 document.querySelectorAll('[data-open-smtp-assistant]').forEach(button=>button.addEventListener('click',()=>{
   const selectors=['.smtp-assistant-toggle','.smtp-assistant-launcher','[data-smtp-assistant-toggle]','.smtp-ai-toggle'];
   const launcher=selectors.map(selector=>document.querySelector(selector)).find(Boolean);
   if(launcher){launcher.click();return;}
   const widget=document.querySelector('.smtp-assistant,.smtp-ai-assistant');
   if(widget){widget.scrollIntoView({behavior:'smooth',block:'center'});return;}
   window.location.href=(document.documentElement.lang||'en').startsWith('ar')?'#contact':'#contact';
 }));
})();


// v6.0 secure contact form
(() => {
 const form=document.getElementById('smt-contact-form');
 if(!form||typeof smtTheme==='undefined') return;
 const status=form.querySelector('.form-status'),button=form.querySelector('button[type="submit"]');
 form.addEventListener('submit',async event=>{
   event.preventDefault();
   if(!form.reportValidity()) return;
   const original=button.textContent;
   button.disabled=true; button.textContent=smtTheme.messages.sending; status.textContent=''; status.className='form-status';
   const data=new FormData(form); data.append('action','smt_contact_submit'); data.append('nonce',smtTheme.contactNonce);
   try{
     const response=await fetch(smtTheme.ajaxUrl,{method:'POST',body:data,credentials:'same-origin'});
     const result=await response.json();
     if(!response.ok||!result.success) throw new Error(result?.data?.message||smtTheme.messages.error);
     status.textContent=result.data.message; status.classList.add('is-success'); form.reset();
   }catch(error){status.textContent=error.message||smtTheme.messages.error;status.classList.add('is-error');}
   finally{button.disabled=false;button.textContent=original;}
 });
})();
