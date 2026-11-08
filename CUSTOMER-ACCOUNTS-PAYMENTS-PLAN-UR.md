# Hammad Graphic — Customer Accounts & Online Payments (Urdu Plan)

## Selected customer experience
- Registration fields: full name, email, mobile/WhatsApp, unique username, password.
- Verify email before activating the account. Store passwords only as PHP password_hash() hashes.
- Login by username or email; secure session rotation, CSRF protection, login throttling, logout.
- Customer dashboard: profile, own orders, quote/invoice, payment history, receipt/status.
- Admin remains a separate role and sign-in. Admin can manage customer status, link legacy orders after identity checks, issue quotes, and review payments.

## Payment flow
1. Admin creates/updates a quote for a customer-owned order.
2. Server creates a payment transaction with a unique idempotency key.
3. Server requests a hosted checkout session from the selected gateway.
4. Customer is redirected to the gateway's hosted checkout page.
5. Gateway sends a signed server-to-server webhook.
6. Server verifies the signature and transaction amount/currency/reference with the gateway before marking a transaction paid.
7. Customer dashboard and Admin show the verified result.

A browser return URL alone is never proof of payment. Never store card PAN/CVV, wallet PIN, OTP, or gateway secret keys in the database or Git repository.

## Existing data compatibility
- The migration adds nullable orders.customer_id, preserving all existing orders.
- Existing order/payment columns and the current manual proof flow are not deleted by this migration.
- Legacy orders must only be linked to a customer after verifying email/mobile ownership. Do not automatically attach by an unverified email string.
- Keep the live includes/config.php and its hosting credentials untouched. Do not upload the repository's config.php over the live file.

## Files planned for the next implementation slices
- register.php, verify-email.php, login.php, logout.php, forgot-password.php, reset-customer-password.php
- customer/index.php, customer/profile.php, customer/orders.php, customer/order.php, customer/payments.php
- includes/customer-auth.php, includes/payment-gateway.php, includes/payment-provider-safepay.php (only after provider selection and merchant credentials are available)
- create-payment.php, payment-return.php, payment-webhook.php
- admin/customers.php, admin/customer.php, admin/payments.php
- database/customer-accounts-payments-migration.sql

## Deployment gates
- Back up the live database and files first.
- Run migration once in phpMyAdmin; confirm the tables and nullable foreign key.
- Test registration, verification, duplicate email/mobile/username, login throttling, password reset, and cross-customer access isolation.
- Test gateway sandbox including success, cancel, failure, duplicate webhook, wrong amount/currency, invalid signature, and delayed webhook.
- Only deploy live after a merchant account is approved and sandbox tests pass.
