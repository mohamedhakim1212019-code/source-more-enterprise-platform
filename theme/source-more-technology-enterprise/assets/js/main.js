document.addEventListener('DOMContentLoaded',()=>{
 const toggle=document.querySelector('.menu-toggle');
 const nav=document.querySelector('.primary-nav');
 const header=document.getElementById('site-header');
 const backdrop=document.querySelector('.nav-backdrop');
 const mobileBreakpoint=1100;

 const closeSubmenus=(except=null)=>{
  document.querySelectorAll('.menu-item-has-children.is-submenu-open').forEach(item=>{
   if(item===except)return;
   item.classList.remove('is-submenu-open');
   item.querySelector(':scope > .submenu-toggle')?.setAttribute('aria-expanded','false');
  });
 };
 const closeNav=()=>{
  if(!toggle||!nav)return;
  nav.classList.remove('is-open');
  toggle.setAttribute('aria-expanded','false');
  toggle.setAttribute('aria-label',smtTheme?.messages?.openNavigation||'Open navigation');
  document.body.classList.remove('nav-open');
  closeSubmenus();
 };
 const openNav=()=>{
  if(!toggle||!nav)return;
  nav.classList.add('is-open');
  toggle.setAttribute('aria-expanded','true');
  toggle.setAttribute('aria-label',smtTheme?.messages?.closeNavigation||'Close navigation');
  document.body.classList.add('nav-open');
 };

 const hoverCloseTimers=new WeakMap();
 const cancelHoverClose=item=>{
  const timer=hoverCloseTimers.get(item);
  if(timer)clearTimeout(timer);
  hoverCloseTimers.delete(item);
 };
 const setSubmenuState=(item,button,isOpen)=>{
  cancelHoverClose(item);
  item.classList.toggle('is-submenu-open',isOpen);
  button?.setAttribute('aria-expanded',String(isOpen));
 };
 const scheduleHoverClose=(item,button)=>{
  cancelHoverClose(item);
  hoverCloseTimers.set(item,setTimeout(()=>{
   setSubmenuState(item,button,false);
   hoverCloseTimers.delete(item);
  },320));
 };

 document.querySelectorAll('.enterprise-menu .menu-item-has-children').forEach((item,index)=>{
  const link=item.querySelector(':scope > a');
  const submenu=item.querySelector(':scope > .sub-menu');
  if(!link||!submenu)return;
  if(!submenu.id)submenu.id=`smt-submenu-${index+1}`;
  const button=document.createElement('button');
  button.type='button';
  button.className='submenu-toggle';
  button.setAttribute('aria-expanded','false');
  button.setAttribute('aria-controls',submenu.id);
  button.setAttribute('aria-label',`${smtTheme?.messages?.toggleSubmenu||'Toggle submenu'}: ${link.textContent.trim()}`);
  item.insertBefore(button,submenu);

  /* Mobile/tablet: explicit accordion button. */
  button.addEventListener('click',event=>{
   event.preventDefault();
   event.stopPropagation();
   if(innerWidth>mobileBreakpoint)return;
   const willOpen=!item.classList.contains('is-submenu-open');
   closeSubmenus(willOpen?item:null);
   setSubmenuState(item,button,willOpen);
  });

  /* Desktop: hover opens, then a short close delay protects pointer travel. */
  item.addEventListener('pointerenter',()=>{
   if(innerWidth<=mobileBreakpoint)return;
   closeSubmenus(item);
   setSubmenuState(item,button,true);
  });
  item.addEventListener('pointerleave',()=>{
   if(innerWidth<=mobileBreakpoint)return;
   scheduleHoverClose(item,button);
  });
  submenu.addEventListener('pointerenter',()=>{
   if(innerWidth<=mobileBreakpoint)return;
   setSubmenuState(item,button,true);
  });
  submenu.addEventListener('pointerleave',()=>{
   if(innerWidth<=mobileBreakpoint)return;
   scheduleHoverClose(item,button);
  });
  link.addEventListener('focus',()=>{
   if(innerWidth<=mobileBreakpoint)return;
   closeSubmenus(item);
   setSubmenuState(item,button,true);
  });
 });

 toggle?.addEventListener('click',()=>nav?.classList.contains('is-open')?closeNav():openNav());
 backdrop?.addEventListener('click',closeNav);
 document.addEventListener('click',event=>{
  if(innerWidth>mobileBreakpoint&&!event.target.closest('.enterprise-menu .menu-item-has-children'))closeSubmenus();
 });
 document.addEventListener('keydown',event=>{
  if(event.key==='Escape'){
   if(nav?.classList.contains('is-open')){closeNav();toggle?.focus();}
   else closeSubmenus();
  }
 });
 document.querySelectorAll('.primary-nav a').forEach(link=>link.addEventListener('click',()=>{
  if(innerWidth<=mobileBreakpoint)closeNav();
 }));
 addEventListener('resize',()=>{if(innerWidth>mobileBreakpoint)closeNav();},{passive:true});
 addEventListener('scroll',()=>header&&header.classList.toggle('is-scrolled',scrollY>12),{passive:true});
 header?.classList.toggle('is-scrolled',scrollY>12);

 const io='IntersectionObserver'in window?new IntersectionObserver(es=>es.forEach(e=>e.isIntersecting&&e.target.classList.add('is-visible')),{threshold:.12}):null;
 document.querySelectorAll('.reveal').forEach(x=>io?io.observe(x):x.classList.add('is-visible'));
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
