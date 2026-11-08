# Hammad Graphic — WhatsApp Registration OTP Setup (Urdu)

## What this feature does
- A new customer registers with name, email, WhatsApp number, username and password.
- The customer record remains `pending_verification` until the 6-digit WhatsApp code is entered.
- OTP is valid for 5 minutes, can be used once, and is stored only as a password hash.
- Maximum 5 wrong attempts per code; resend is limited to once per 60 seconds.
- Only an `active` customer can log in.
- This uses Meta WhatsApp Cloud API outbound HTTPS. It does not need a webhook callback.

## 1. Meta WhatsApp Cloud API account
1. Open https://developers.facebook.com/ and sign in with the Meta account managing your business.
2. Create a Meta App and choose the WhatsApp use case / product.
3. In WhatsApp > API Setup, note the **Phone Number ID** and **WhatsApp Business Account ID (WABA ID)**.
4. Add and verify a WhatsApp Business sending number. Complete any required business/phone registration.
5. For real customers, create a System User in Meta Business Settings and generate a long-lived access token with the required `whatsapp_business_messaging` permission. Do not rely on the short-lived test token for production.
6. Add your own phone as a test recipient first. Follow Meta's test-recipient and opt-in rules.

## 2. Create the authentication template
Create an authentication template in WhatsApp Manager or Meta's template API:
- Name: `hammad_graphic_registration_otp`
- Category: `AUTHENTICATION`
- Language: `English (US) / en_US`
- OTP button: `COPY_CODE`
- Expiry: 5 minutes
- Security recommendation: enabled

Meta controls the wording for authentication templates. Wait until the template status is **APPROVED** before testing. The application sends both the body and copy-code button parameters as required by the template. If you choose a different template name or language, update the corresponding config values.

## 3. Create private host configuration
On InfinityFree File Manager, open your website's `htdocs/includes/` folder.
1. Download `includes/whatsapp-config.php.example` from the feature branch.
2. Create a new file named exactly `whatsapp-config.php` in the HOST's `includes` folder (not GitHub).
3. Copy the example file contents and replace:
   - `WA_GRAPH_VERSION`: a currently supported version shown by Meta's Graph API documentation/dashboard.
   - `WA_PHONE_NUMBER_ID`: Meta's Phone Number ID (not the public phone number).
   - `WA_ACCESS_TOKEN`: your long-lived System User token.
   - `WA_OTP_TEMPLATE`: `hammad_graphic_registration_otp`
   - `WA_OTP_LANGUAGE`: `en_US`
4. Save. Never upload the real config to GitHub, never paste the token in chat, and never expose it in browser JavaScript.

## 4. Database migration
1. Back up the live database in phpMyAdmin first.
2. Confirm the main `customer-accounts-payments-migration.sql` has already been applied successfully.
3. In phpMyAdmin select the Hammad Graphic database, open SQL, and run `database/customer-whatsapp-otp-migration.sql` exactly once.
4. Confirm table `customer_registration_otps` exists.
Do not rerun the main migration blindly: it contains an ALTER TABLE for orders and may fail if already applied.

## 5. Upload code (after testing)
Upload the feature-branch files to the matching locations:
- `register.php` (replace existing)
- `verify-registration.php` (new)
- `resend-registration-otp.php` (new)
- `includes/whatsapp-otp.php` (new)
- `database/customer-whatsapp-otp-migration.sql` (new; SQL file itself is not a public page)
- `includes/whatsapp-config.php.example` (reference only; do not use as live secrets file)

Do not overwrite the live `includes/config.php`. Preserve all existing database credentials and INSTALL_KEY. The real `includes/whatsapp-config.php` is created manually on the host and is ignored by Git.

## 6. Test sequence
1. Use your own WhatsApp number as a Meta test recipient.
2. Register a new test customer with a number in international format, e.g. `+923001234567`.
3. Check that WhatsApp receives the authentication code.
4. Enter the code within 5 minutes; the account should become active and the customer dashboard should open.
5. Try a wrong code; it must not activate the account. After 5 wrong attempts, request a new code.
6. Try resend twice quickly; the second request should be rate-limited.
7. Log out and log in with username/password; an active account should log in normally.
8. Check phpMyAdmin: `customers.account_status` should be `active`; the OTP row should have been deleted after success.

## InfinityFree notes
- The WhatsApp request is an outbound HTTPS call to Meta, which InfinityFree documents as supported on standard HTTPS.
- InfinityFree free hosting blocks inbound API/webhook callbacks; this OTP flow does not depend on a callback.
- If cURL is unavailable or Meta API is blocked, OTP delivery will fail safely and the customer will remain pending. Do not activate the account manually.
- WhatsApp template messaging may incur Meta/provider charges and is subject to Meta eligibility, opt-in and template approval rules.

## Safety / rollout
- Do not enable this on the live site until a backup, SQL migration, host-only secrets file and successful test are complete.
- Do not paste access tokens into GitHub, WhatsApp chats or ChatGPT.
- Do not use unofficial WhatsApp Web automation libraries for OTP delivery.
- This verifies the WhatsApp number at registration. It does not yet implement OTP on every subsequent login, password-reset OTP, or delivery-status webhooks.
