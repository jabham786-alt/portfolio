<?php
/*
 * Hammad Graphic — Website Design & Development Services
 * Included by index.php. Keep this file in the website root.
 */
?>
<section class="section-wrap section-block hg-web-services" id="website-services">
  <style>
    .hg-web-services .hg-web-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
    .hg-web-services .hg-web-card{position:relative;display:flex;flex-direction:column;min-height:360px;padding:27px;background:var(--surface);border:1px solid var(--line)}
    .hg-web-services .hg-web-card.featured{border-color:var(--accent);background:linear-gradient(145deg,var(--surface),color-mix(in srgb,var(--accent) 9%,var(--surface)))}
    .hg-web-services .hg-web-tag{font:700 9px var(--display);letter-spacing:1.5px;color:var(--accent)}
    .hg-web-services .hg-web-card h3{font:600 21px var(--display);margin:24px 0 8px}
    .hg-web-services .hg-web-price{font:700 clamp(31px,3vw,42px)/1.1 var(--display);letter-spacing:-1.5px;margin:10px 0 5px}
    .hg-web-services .hg-web-price small{font:500 11px var(--body);letter-spacing:0;color:var(--muted)}
    .hg-web-services .hg-web-desc{font-size:12px;line-height:1.8;color:var(--muted);margin:12px 0 18px}
    .hg-web-services .hg-web-list{list-style:none;padding:0;margin:0 0 25px;display:grid;gap:10px}
    .hg-web-services .hg-web-list li{font-size:11px;color:var(--text);display:flex;gap:9px;line-height:1.6}
    .hg-web-services .hg-web-list li:before{content:'✓';color:var(--accent);font-weight:700}
    .hg-web-services .hg-web-card .button{margin-top:auto;align-self:flex-start}
    .hg-web-services .hg-web-note{font-size:10px;line-height:1.7;color:var(--muted);margin:22px 0 0}
    @media(max-width:850px){.hg-web-services .hg-web-grid{grid-template-columns:1fr 1fr}}
    @media(max-width:600px){.hg-web-services .hg-web-grid{grid-template-columns:1fr}.hg-web-services .hg-web-card{min-height:0}}
  </style>
  <div class="section-heading">
    <div><div class="eyebrow">WEBSITE DESIGN & DEVELOPMENT</div><h2>Build your next<br><em>digital home.</em></h2></div>
    <p>From a polished interface to a complete working website, choose the level of service that fits your business.</p>
  </div>
  <div class="hg-web-grid">
    <article class="hg-web-card">
      <span class="hg-web-tag">01 / DESIGN</span>
      <h3>UI / UX Website Design</h3>
      <div class="hg-web-price">PKR 5,000 <small>/ starting price</small></div>
      <p class="hg-web-desc">A clean, user-friendly website layout planned around your brand and visitors.</p>
      <ul class="hg-web-list">
        <li>Website page layouts and visual design</li>
        <li>Responsive desktop and mobile concepts</li>
        <li>Colour, typography and user-flow planning</li>
        <li>Design handoff for development</li>
      </ul>
      <a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%27m%20interested%20in%20UI%2FUX%20website%20design.">Discuss this service <span>↗</span></a>
    </article>
    <article class="hg-web-card">
      <span class="hg-web-tag">02 / DEVELOPMENT</span>
      <h3>Full Website Development</h3>
      <div class="hg-web-price">PKR 15,000 <small>/ starting price</small></div>
      <p class="hg-web-desc">A complete functional website developed to present your business online.</p>
      <ul class="hg-web-list">
        <li>Website development and responsive setup</li>
        <li>Core pages and navigation</li>
        <li>Contact form and WhatsApp connection</li>
        <li>Basic testing before handover</li>
      </ul>
      <a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%27m%20interested%20in%20full%20website%20development.">Discuss this service <span>↗</span></a>
    </article>
    <article class="hg-web-card featured">
      <span class="hg-web-tag">03 / COMPLETE PACKAGE + ONGOING SUPPORT</span>
      <h3>Website Design, Development & Support</h3>
      <div class="hg-web-price">PKR 30,000 <small>/ starting price</small></div>
      <p class="hg-web-desc">A complete website package with design, development and ongoing support while your website remains active.</p>
      <ul class="hg-web-list">
        <li>Custom website design and development</li>
        <li>Responsive pages for mobile and desktop</li>
        <li>Ongoing website maintenance and support</li>
        <li>Website text, image and layout changes on request</li>
        <li>Help with website issues and updates</li>
      </ul>
      <a class="button button-primary" href="https://wa.me/923067115462?text=Hello%20Hammad%2C%20I%27m%20interested%20in%20the%20PKR%2030%2C000%20website%20design%2C%20development%20and%20ongoing%20support%20package.">Choose complete package <span>↗</span></a>
    </article>
  </div>
  <p class="hg-web-note">All prices are starting prices in PKR. Final quotation depends on the number of pages, required features and project scope. Domain, hosting and paid third-party services or renewals are separate unless agreed in writing.</p>
</section>
