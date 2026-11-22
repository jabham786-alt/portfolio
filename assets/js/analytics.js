(function () {
  'use strict';
  if (document.body && document.body.classList.contains('admin-dashboard')) return;

  var visitorKey = 'hg_analytics_visitor';
  var visitorId = '';
  try {
    visitorId = localStorage.getItem(visitorKey) || '';
    if (!/^[a-f0-9]{32,64}$/.test(visitorId)) {
      var bytes = new Uint8Array(24);
      if (window.crypto && crypto.getRandomValues) crypto.getRandomValues(bytes);
      else for (var i = 0; i < bytes.length; i++) bytes[i] = Math.floor(Math.random() * 256);
      visitorId = Array.from(bytes, function (b) { return b.toString(16).padStart(2, '0'); }).join('');
      localStorage.setItem(visitorKey, visitorId);
    }
  } catch (_) {
    visitorId = '';
  }
  if (!visitorId) return;

  function send(eventName) {
    try {
      var payload = JSON.stringify({
        event: eventName,
        visitor: visitorId,
        page: window.location.pathname
      });
      if (navigator.sendBeacon) {
        navigator.sendBeacon('analytics-event.php', new Blob([payload], { type: 'application/json' }));
      } else {
        fetch('analytics-event.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: payload,
          keepalive: true,
          credentials: 'same-origin'
        }).catch(function () {});
      }
    } catch (_) {}
  }

  send('page_view');
  document.addEventListener('click', function (event) {
    var link = event.target && event.target.closest ? event.target.closest('a,button') : null;
    if (!link) return;
    var href = (link.getAttribute('href') || '').toLowerCase();
    var label = (link.textContent || '').toLowerCase();
    if (href.indexOf('wa.me/') !== -1 || href.indexOf('whatsapp') !== -1) send('whatsapp_click');
    else if (href.indexOf('order.php') !== -1 || label.indexOf('start a project') !== -1 || label.indexOf('place order') !== -1) send('order_click');
    else if (href.indexOf('#contact') !== -1 || href.indexOf('mailto:') === 0 || link.id === 'contactLauncher') send('contact_click');
  }, true);
})();
