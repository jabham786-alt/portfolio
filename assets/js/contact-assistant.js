(() => {
const launch=document.getElementById('contactLauncher'),panel=document.getElementById('contactPanel'),close=document.getElementById('panelClose'),form=document.getElementById('contactAssistantForm'),input=document.getElementById('contactMessageInput'),messages=document.getElementById('contactMessages');
if(!launch||!panel||!form||!input||!messages)return;
const fixedReply='Please contact us on WhatsApp: +923067115462';
const open=value=>{panel.hidden=!value;launch.setAttribute('aria-expanded',String(value));if(value)input.focus()};
launch.addEventListener('click',()=>open(panel.hidden));close?.addEventListener('click',()=>open(false));
form.addEventListener('submit',event=>{event.preventDefault();if(!input.value.trim())return;const reply=document.createElement('div');reply.className='bot-message';reply.textContent=fixedReply;messages.appendChild(reply);input.value='';messages.scrollTop=messages.scrollHeight});
})();