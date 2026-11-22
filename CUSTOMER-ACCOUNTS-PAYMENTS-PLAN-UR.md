# Hammad Graphic — کسٹمر اکاؤنٹس اور منظوری کا نظام

## رجسٹریشن سے اکاؤنٹ ایکٹیویشن تک
1. کسٹمر نام، ای میل، WhatsApp نمبر، یوزرنیم اور پاس ورڈ سے رجسٹر کرے گا۔
2. نیا اکاؤنٹ `pending_admin` حالت میں رہے گا؛ اس دوران لاگ اِن اور آرڈر جمع نہیں ہو سکیں گے۔
3. ایڈمن پینل کے **Customer approvals** حصے میں نئی درخواست نظر آئے گی۔
4. ایڈمن Approve کرے گا تو سسٹم ایک خفیہ، 256-bit، ایک بار استعمال ہونے والا activation token بنائے گا۔ ڈیٹابیس میں صرف SHA-256 hash محفوظ ہوگا۔
5. ایڈمن Copy activation link یا Open WhatsApp message استعمال کرے گا۔ WhatsApp خودکار طور پر کوئی پیغام نہیں بھیجے گا؛ ایڈمن کو خود Send دبانا ہوگا۔
6. لنک 48 گھنٹے تک کارآمد ہے۔ کسٹمر لنک کھول کر Activate دبائے گا، پھر اکاؤنٹ active ہوگا اور کسٹمر ڈیش بورڈ کھل جائے گا۔
7. نیا لنک بنانے پر پچھلا لنک فوراً غیر مؤثر ہوگا۔ Reject کرنے پر تمام غیر استعمال شدہ لنکس منسوخ ہوں گے۔

## ضروری فائلیں
- `register.php` — کسٹمر رجسٹریشن
- `login.php` — کسٹمر لاگ اِن
- `activate-account.php` — لنک کے ذریعے ایکٹیویشن
- `includes/customer-auth.php` — کسٹمر سیشن اور لاگ اِن
- `includes/customer-activation.php` — token بنانے اور URL کی مددگار functions
- `admin/customers.php` — ایڈمن کی منظوری، مسترد کرنا اور WhatsApp لنک بنانا
- `database/customer-manual-approval-migration.sql` — اس فیچر کا ڈیٹابیس اپڈیٹ

## InfinityFree پر لگانے کا طریقہ
1. پہلے موجودہ ویب سائٹ کی فائلوں اور ڈیٹابیس کا backup لیں۔
2. تصدیق کریں کہ بنیادی `customer-accounts-payments-migration.sql` پہلے کامیابی سے چل چکی ہے۔ اگر نہیں، تو اسے پہلے صرف ایک مرتبہ چلائیں۔
3. phpMyAdmin میں درست Hammad Graphic ڈیٹابیس منتخب کریں اور `customer-manual-approval-migration.sql` صرف ایک مرتبہ چلائیں۔
4. اس برانچ کی نئی/تبدیل شدہ PHP فائلیں ان کے اسی راستے پر اپ لوڈ کریں۔ `includes/config.php` کو GitHub والی فائل سے ہرگز replace نہ کریں۔
5. اپنی live `includes/config.php` میں `SITE_URL` کو اپنی اصل HTTPS ویب سائٹ کے پتے پر رکھیں، مثال: `https://hammadgraphic.site.je`۔
6. `/register.php` پر ایک test customer بنائیں۔
7. `/admin/login.php` سے ایڈمن لاگ اِن کریں، پھر `/admin/customers.php` کھول کر Approve کریں۔
8. لنک کاپی کریں اور اپنے WhatsApp سے test customer کو خود بھیجیں۔ لنک کھول کر Activate دبائیں، پھر لاگ اِن اور customer dashboard آزمائیں۔
9. تصدیق کریں کہ اسی کسٹمر کے آرڈرز صرف اس کے اکاؤنٹ میں دکھائی دیں۔

## سیکیورٹی اور حدود
- پاس ورڈ `password_hash()` سے محفوظ ہوتے ہیں؛ plain text میں محفوظ نہیں ہوتے۔
- Activation token 32 random bytes سے بنتا ہے، صرف hash ڈیٹابیس میں محفوظ ہوتا ہے، 48 گھنٹے بعد ختم اور ایک بار استعمال کے بعد ناقابلِ استعمال ہوتا ہے۔
- لنک کھولنے (GET) سے اکاؤنٹ خودکار فعال نہیں ہوتا؛ کسٹمر کو فارم پر Activate دبانا ضروری ہے تاکہ WhatsApp link preview bots لنک استعمال نہ کر سکیں۔
- ایڈمن کے تمام فیصلے CSRF محفوظ POST درخواستوں سے ہوتے ہیں۔
- کسی WhatsApp API، OTP، SMS یا paid message provider کی ضرورت نہیں۔
- یہ برانچ ابھی live hosting پر deploy نہیں ہوئی۔ live deployment سے پہلے migration، HTTPS، PHP syntax اور مکمل registration/approval/activation/login/order tests ضروری ہیں۔

## آن لائن ادائیگی
Safepay کی ادائیگی کی بنیاد الگ فیچر ہے۔ InfinityFree پر inbound webhooks کی پابندی کے باعث signed server-side payment verification کو قابلِ اعتماد طریقے سے مکمل کیے بغیر کسی ادائیگی کو paid قرار نہیں دیا جائے گا۔
