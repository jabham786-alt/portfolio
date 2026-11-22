# Hammad Graphic — لائیو ویب سائٹ پر Customer Accounts لگانے کی مکمل ہدایات

## GitHub فائلیں
- مکمل برانچ: https://github.com/jabham786-alt/portfolio/tree/feature/customer-accounts-payments
- ZIP ڈاؤن لوڈ: https://github.com/jabham786-alt/portfolio/archive/refs/heads/feature/customer-accounts-payments.zip
- Pull Request: https://github.com/jabham786-alt/portfolio/pull/2

## اپ لوڈ سے پہلے
1. InfinityFree File Manager میں اپنی ویب سائٹ کے document root (عام طور پر `htdocs`) کا backup لیں۔
2. phpMyAdmin سے موجودہ ڈیٹابیس کا Export لے کر محفوظ رکھیں۔
3. تصدیق کریں کہ آپ درست ڈومین اور درست ڈیٹابیس میں کام کر رہے ہیں۔

## فائلیں اپ لوڈ کرنا
1. اوپر دیا گیا ZIP ڈاؤن لوڈ اور اپنے کمپیوٹر پر Extract کریں۔
2. Extract ہونے والے فولڈر کے اندر موجود ویب سائٹ کی تمام فائلیں اور فولڈرز اپنے domain کے `htdocs` میں اپ لوڈ کریں۔
3. موجودہ فائلوں کو replace کرتے وقت یہ فائل ہرگز replace نہ کریں: `includes/config.php`۔ اس میں live MySQL password اور INSTALL_KEY محفوظ ہیں۔
4. اگر `storage/uploads` میں موجود آپ کے کسٹمر یا portfolio uploads ہیں تو انہیں حذف یا replace نہ کریں؛ پہلے backup ضرور رکھیں۔
5. GitHub ZIP میں موجود `includes/config.php` ایک template ہے؛ اسے live hosting پر upload نہ کریں۔

## ڈیٹابیس migration — ترتیب لازمی ہے
1. phpMyAdmin میں Hammad Graphic کا live database منتخب کریں۔
2. اگر `database/customer-accounts-payments-migration.sql` پہلے کامیابی سے نہیں چلائی گئی تو پہلے اسے ایک مرتبہ چلائیں۔ اگر پہلے چل چکی ہے تو دوبارہ نہ چلائیں کیونکہ اس میں `ALTER TABLE orders` ہے۔
3. اس کے بعد `database/customer-manual-approval-migration.sql` ایک مرتبہ چلائیں۔
4. دوسرے migration کے بعد `customers.account_status` میں یہ statuses ہونے چاہئیں: `pending_admin`, `approved_pending_activation`, `active`, `rejected`, `suspended`۔
5. `customer_activation_tokens` نام کی table موجود ہونی چاہیے۔

## Live config
اپنی موجودہ live `includes/config.php` کھولیں اور صرف یہ تصدیق کریں کہ `SITE_URL` آپ کے اصل HTTPS domain کے برابر ہے، مثال:
`define('SITE_URL', 'https://hammadgraphic.site.je');`
اپنی اصل DB settings، DB password اور INSTALL_KEY کو تبدیل نہ کریں۔ یہ values کسی کو بھی نہ بھیجیں۔

## اہم ویب لنکس
- Public website: https://hammadgraphic.site.je/
- Customer registration: https://hammadgraphic.site.je/register.php
- Customer login: https://hammadgraphic.site.je/login.php
- Customer dashboard: https://hammadgraphic.site.je/customer/index.php
- Admin login: https://hammadgraphic.site.je/admin/login.php
- Admin customer approvals: https://hammadgraphic.site.je/admin/customers.php

نوٹ: Customer registration/login اور approvals کے لنکس صرف فائلیں اپ لوڈ کرنے اور دونوں ضروری migrations کامیاب ہونے کے بعد کام کریں گے۔ یہ فیچر ابھی production پر deploy نہیں کیا گیا۔

## آخری ٹیسٹ
1. `/register.php` پر اپنا test customer بنائیں۔
2. `/admin/login.php` سے ایڈمن لاگ اِن کریں اور `/admin/customers.php` کھولیں۔
3. test customer کے سامنے Approve دبائیں۔
4. Copy activation link دبائیں یا Open WhatsApp message کھول کر خود Send کریں۔
5. test customer لنک کھولے اور Activate دبائے۔
6. تصدیق کریں کہ وہ `/customer/index.php` پر لاگ اِن ہو گیا ہے۔
7. Logout کریں، دوبارہ login کریں، اور test order جمع کریں۔
8. کسی دوسرے customer سے پہلے customer کا order دیکھنے کی کوشش کریں؛ اسے نظر نہیں آنا چاہیے۔

## موجودہ حدود
- WhatsApp خودکار طور پر کوئی پیغام نہیں بھیجتا۔ Admin خود WhatsApp میں Send کرے گا؛ اس لیے کوئی WhatsApp API یا OTP فیس نہیں۔
- Activation link ایک بار استعمال ہوتا ہے اور 48 گھنٹے بعد ختم ہوتا ہے۔
- Safepay online checkout ابھی production-ready یا live نہیں ہے۔ Browser redirect کی بنیاد پر payment کو paid نہ کریں؛ verified server-side webhook کی ضرورت ہے۔
- live deploy سے پہلے PHP syntax اور اصل InfinityFree/MySQL پر end-to-end ٹیسٹ ضروری ہیں۔
