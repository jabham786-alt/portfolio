# Safepay setup for Hammad Graphic (Urdu)

## Current state
- Safepay is selected, but no merchant/sandbox account credentials have been supplied.
- Do not enable live payments or mark an order paid from a browser redirect.
- Never add secret keys to GitHub, screenshots, chat, or public files.
- Keep test and production credentials separate. The webhook HMAC secret is environment-specific.

## Step 1 — create a Safepay sandbox account
1. Open the official API docs: https://apidocs.getsafepay.com/
2. Follow the docs' sandbox sign-up path and create your own account.
3. Sign in to the sandbox dashboard.
4. Open the Developers / API settings area and create or copy the sandbox API credentials.
5. Configure a webhook endpoint only after the receiving endpoint is ready. The endpoint must validate the `X-SFPY-SIGNATURE` HMAC over the raw request body.
6. Keep these values private: merchant secret/API key, client ID, and webhook HMAC secret.

## Step 2 — InfinityFree limitation (must be resolved before live activation)
InfinityFree free hosting blocks inbound automated API requests and third-party webhook POSTs through its browser-security layer. Safepay's hosted checkout guide expects a server webhook to confirm payment. A normal customer return/redirect is not proof that money was received.

Therefore:
- Checkout creation may be possible through outbound HTTPS from PHP.
- A Safepay webhook sent directly to this InfinityFree site is not a reliable supported payment-confirmation path.
- Do not mark `payment_transactions.payment_status = 'paid'` from a query parameter, browser return, screenshot, or unverified client request.
- Keep the current manual payment page and customer order history until a secure confirmation channel is deployed.

## Supported paths while keeping the public site on InfinityFree
A separate HTTPS webhook relay (for example a Cloudflare Worker with durable storage) can receive Safepay webhooks, verify the HMAC signature, deduplicate provider event IDs, and expose only authenticated server-to-server status lookup to the PHP site. This relay is a separate deployment and needs its own secrets and tests; it is not included or activated by this branch yet.

Alternative: move only the payment/API endpoint to a hosting provider that supports inbound HTTPS webhooks, while keeping the public portfolio pages on InfinityFree.

## Required production tests
- Successful sandbox payment
- Cancelled and failed checkout
- Invalid webhook signature rejected
- Duplicate webhook idempotently ignored
- Wrong amount, currency, merchant or order reference rejected
- Delayed webhook and customer closing the browser before return
- Customer A cannot inspect Customer B's order or payment
- Admin confirms paid status only from a verified provider event/API response

## Official references
- Safepay API reference: https://apidocs.getsafepay.com/
- Safepay PHP SDK: https://github.com/getsafepay/safepay-php
- Safepay Express Checkout guide: https://safepay.alihamas.pk/docs/payments/get-started/express-checkout
- InfinityFree API access limitation: https://forum.infinityfree.com/t/why-isnt-api-access-working-on-my-website/115198/1
- InfinityFree inbound POST/webhook limitation: https://forum.infinityfree.com/t/can-i-make-post-requests-to-an-endpoint-on-my-website/113951

## Customer-account implementation notes
- Registration collects name, email, mobile/WhatsApp, username and password.
- Passwords are stored using PHP `password_hash()`; login uses `password_verify()`.
- Email/mobile are collected but not yet verified by OTP/email link. Configure a trusted SMTP/OTP delivery provider before treating them as verified identity or enabling password reset.
- Existing guest orders are not automatically attached to new customer accounts. Link them only after verifying ownership.
