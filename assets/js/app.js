(() => {
const root=document.documentElement,btn=document.getElementById('themeToggle');
let saved=null;try{saved=localStorage.getItem('hg-theme')}catch(e){}
if(saved==='light'||saved==='dark')root.dataset.theme=saved;
const paint=()=>{if(btn)btn.querySelector('.theme-icon').textContent=root.dataset.theme==='dark'?'☼':'☾'};paint();
btn?.addEventListener('click',()=>{root.dataset.theme=root.dataset.theme==='dark'?'light':'dark';try{localStorage.setItem('hg-theme',root.dataset.theme)}catch(e){}paint()});
const menu=document.getElementById('menuToggle'),nav=document.getElementById('mainNav');
menu?.addEventListener('click',()=>{const open=menu.getAttribute('aria-expanded')!=='true';menu.setAttribute('aria-expanded',String(open));nav?.classList.toggle('is-open',open)});
nav?.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('is-open');menu?.setAttribute('aria-expanded','false')}));
})();