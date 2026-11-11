(() => {
  const root=document.documentElement;
  const themeButtons=document.querySelectorAll('[data-theme-toggle]');
  let saved=null; try{saved=localStorage.getItem('hg-theme')}catch(e){}
  if(saved==='light'||saved==='dark')root.dataset.theme=saved;
  const paint=()=>themeButtons.forEach(btn=>{btn.textContent=root.dataset.theme==='dark'?'☼':'☾';btn.setAttribute('aria-label',root.dataset.theme==='dark'?'Switch to light mode':'Switch to dark mode')});
  paint();
  themeButtons.forEach(btn=>btn.addEventListener('click',()=>{root.dataset.theme=root.dataset.theme==='dark'?'light':'dark';try{localStorage.setItem('hg-theme',root.dataset.theme)}catch(e){}paint()}));
  const sidebar=document.querySelector('.app-sidebar'),menu=document.querySelector('[data-app-menu]');
  menu?.addEventListener('click',()=>sidebar?.classList.toggle('open'));
  document.querySelectorAll('.app-sidebar a').forEach(a=>a.addEventListener('click',()=>sidebar?.classList.remove('open')));
  const slides=[...document.querySelectorAll('.app-slide')],dots=[...document.querySelectorAll('.app-slider-dot')];
  let current=0,timer=null;
  const show=(index)=>{if(!slides.length)return;current=(index+slides.length)%slides.length;slides.forEach((s,i)=>s.classList.toggle('active',i===current));dots.forEach((d,i)=>d.classList.toggle('active',i===current));};
  dots.forEach((dot,i)=>dot.addEventListener('click',()=>{show(i);restart()}));
  const restart=()=>{if(timer)clearInterval(timer);if(slides.length>1)timer=setInterval(()=>show(current+1),5500)};
  show(0);restart();
})();