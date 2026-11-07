# InfinityFree پر Hammad Graphic انسٹال کرنے کا طریقہ

یہ گائیڈ اسی InfinityFree اکاؤنٹ کے لیے ہے جس میں Database پہلے ہی بنایا جا چکا ہے۔

## 1) GitHub سے ویب سائٹ ڈاؤن لوڈ کریں

1. یہ برانچ کھولیں: https://github.com/jabham786-alt/portfolio/tree/build/hammad-graphic-agency
2. **Code** بٹن دبائیں، پھر **Download ZIP** منتخب کریں۔
3. ZIP کو اپنے کمپیوٹر پر Extract کریں۔

## 2) InfinityFree پر فائلیں اپلوڈ کریں

1. InfinityFree Client Area میں اپنا Hosting Account کھولیں۔
2. **File Manager** کھولیں۔
3. اپنے Domain کے لیے دکھائے گئے Document Root میں جائیں (اکثر یہ `htdocs` ہوتا ہے؛ اپنے Domain کا اصل Document Root ہی استعمال کریں)۔
4. Extract کیے گئے پروجیکٹ کے اندر کی تمام فائلیں اور فولڈرز Document Root میں اپلوڈ کریں۔ `index.php` اسی Document Root میں ہونا چاہیے۔
5. یقینی بنائیں کہ `includes`, `admin`, `assets`, `database`, `storage` فولڈرز اپنے اصل ناموں کے ساتھ موجود ہیں۔

## 3) config.php کو محفوظ طریقے سے مکمل کریں

1. GitHub ZIP میں `includes/config.php` کھولیں۔
2. اسے InfinityFree File Manager میں `htdocs/includes/config.php` پر اپلوڈ کریں، موجودہ placeholder فائل کو Replace کریں۔
3. File Manager کے Edit آپشن سے `DB_PASS` میں اپنا حقیقی InfinityFree MySQL Password درج کریں۔
4. `INSTALL_KEY` کو اپنی نجی random key سے بدلیں۔ یہ کم از کم 24 حروف کی ہو۔ اسے کسی کے ساتھ شیئر نہ کریں۔
5. فائل Save کریں۔

آپ کے اسکرین شاٹ کے مطابق باقی Database معلومات پہلے سے درج ہیں:

- Host: `sql108.infinityfree.com`
- Database: `if0_43333038_hammadgraphic`
- Username: `if0_43333038`
- Port: `3306` (اس PHP کنکشن میں الگ سے درج کرنے کی ضرورت نہیں)

**ضروری:** اصل Database Password اور نجی `INSTALL_KEY` کو GitHub پر Commit/Push نہ کریں۔ انہیں صرف ہوسٹنگ پر موجود `includes/config.php` میں درج کریں۔

## 4) Admin اور Database Tables بنائیں

InfinityFree پر آپ کا Database پہلے سے موجود ہے۔ اب انسٹالر اسی Database کے اندر `admins` اور `orders` Tables بنائے گا۔

1. اپنی ویب سائٹ کا یہ ایڈریس کھولیں: `https://YOUR-DOMAIN/admin/setup.php`
2. وہی نجی `INSTALL_KEY` درج کریں جو config.php میں رکھا ہے۔
3. Admin Username بنائیں۔
4. کم از کم 12 حروف کا مضبوط Admin Password بنائیں۔
5. Setup مکمل ہونے پر File Manager میں واپس جائیں۔
6. **`admin/setup.php` کو فوراً Delete کریں۔** یہ قدم لازمی ہے۔

## 5) ویب سائٹ ٹیسٹ کریں

- ہوم پیج: `https://YOUR-DOMAIN/`
- آرڈر فارم: `https://YOUR-DOMAIN/order.php`
- ایڈمن لاگ اِن: `https://YOUR-DOMAIN/admin/login.php`

اگر آرڈر محفوظ نہ ہو تو پہلے `https://YOUR-DOMAIN/admin/diagnose.php` کھولیں، اپنی نجی INSTALL_KEY درج کریں اور نتیجہ دیکھیں۔ تشخیص مکمل ہونے کے بعد یہ فائل سرور سے حذف کریں۔

ایک Test Order جمع کریں، پھر اس کا Reference Number دیکھیں۔ Admin Panel میں اسے Pending کے طور پر کھولیں، Status کو Confirmed اور کام مکمل ہونے پر Completed کریں۔ اگر فائل منسلک کی ہو تو اس کا Download بھی ٹیسٹ کریں۔

یاد رکھیں: Chat Assistant صرف WhatsApp رابطے کی ہدایت دیتا ہے۔ آرڈر محفوظ کرنے کے لیے Home Page یا Chat Widget میں موجود **Open the order form** لنک استعمال کریں۔

## اگر مسئلہ آئے

- Database connection error: Host، Database Name، Username اور Password دوبارہ چیک کریں۔
- 403/404: تمام فائلیں صحیح Document Root میں اپلوڈ ہوئی ہیں یا نہیں چیک کریں۔
- Domain نہ کھلے: InfinityFree کی DNS warning برقرار ہو تو Domain کی propagation مکمل ہونے تک انتظار کرنا پڑ سکتا ہے۔
- آرڈر محفوظ نہ ہو: Admin setup کامیاب ہوا ہے اور MySQL Database درست منتخب ہے، یہ تصدیق کریں۔

## اہم حفاظتی ہدایات

- Admin Password اور Database Password کسی کو نہ دیں۔
- Setup کے بعد `admin/setup.php` حذف کریں۔
- اپنی مقامی edited `config.php` کو Public GitHub پر واپس upload نہ کریں۔
- InfinityFree پر Live order لینے سے پہلے ایک مکمل Test Order ضرور کریں۔
- Free shared hosting پر 100% uptime یا ہر حالت میں بلا رکاوٹ سروس کی ضمانت نہیں دی جا سکتی۔


## Admin Password بھول جانے پر محفوظ Reset

اگر Admin اکاؤنٹ پہلے سے بنا ہوا ہے لیکن Password یاد نہیں:

1. GitHub کی اسی برانچ سے `admin/reset-password.php` ڈاؤن لوڈ کریں۔
2. اسے Live Hosting کے `htdocs/admin/` فولڈر میں عارضی طور پر اپلوڈ کریں۔
3. صرف HTTPS کے ذریعے یہ صفحہ کھولیں: `https://YOUR-DOMAIN/admin/reset-password.php`
4. Live `includes/config.php` میں رکھی اپنی نجی `INSTALL_KEY` درج کریں۔
5. اپنا عین Admin Username درج کریں۔
6. نیا مضبوط Password دو مرتبہ درج کریں (کم از کم 12 حروف)۔
7. کامیابی کا پیغام آنے کے فوراً بعد File Manager سے `admin/reset-password.php` حذف کریں۔
8. اب `admin/login.php` پر نئے Password سے لاگ اِن کریں۔

یہ Reset فائل پرانا Password یا Database Password نہیں دکھاتی۔ یہ موجودہ Admin کا Password نیا hash بنا کر تبدیل کرتی ہے۔ اگر آپ کو نجی `INSTALL_KEY` یاد یا دستیاب نہیں تو پہلے اسے محفوظ طریقے سے اپنے Live `includes/config.php` میں دیکھیں/نیا بنائیں؛ اسے پبلک GitHub پر اپلوڈ نہ کریں۔

**سکیورٹی:** INSTALL_KEY، نیا Admin Password یا Database Password کسی کو نہ بھیجیں۔ Reset کامیاب ہوتے ہی Reset فائل لازماً Live Hosting سے حذف کریں۔


## JazzCash / Easypaisa Manual Payment Setup

یہ خودکار gateway نہیں ہے۔ کسٹمر آپ کے wallet نمبر پر رقم بھیجے گا، Transaction ID اور رسید جمع کرے گا، اور آپ خود رقم وصول ہونے کی تصدیق کے بعد Paid کریں گے۔

1. phpMyAdmin میں اپنی Hammad Graphic database منتخب کریں۔
2. `database/payment-migration.sql` ایک مرتبہ Import کریں۔ اسے دوبارہ نہ چلائیں، ورنہ duplicate-column error آئے گا۔
3. یہ فائلیں اپلوڈ کریں: `payment.php`, `admin/payment-settings.php`, `admin/quote.php`, `admin/payment-proof.php` اور تازہ `admin/index.php`, `admin/order.php`۔
4. Admin login کریں، Dashboard میں Payment settings کھولیں اور صرف وصولی والے JazzCash/Easypaisa نمبر اور account title محفوظ کریں۔
5. Order کھولیں، `Set quote / verify payment` منتخب کریں، PKR رقم مقرر کرکے Payment Link کسٹمر کو بھیجیں۔
6. کسٹمر wallet منتخب کرکے رقم بھیجے، Transaction ID اور رسید جمع کرے۔
7. Admin کے Quote/Payment صفحے میں رسید دیکھیں۔ صرف اپنے wallet میں اصل رقم آنے کی تصدیق کے بعد `Confirm money received` منتخب کریں۔ تبھی Payment Status Paid ہوگا۔

**اہم:** Customer کی screenshot یا Transaction ID اکیلے payment proof نہیں۔ اپنے JazzCash/Easypaisa balance/statement میں رقم آنے کی تصدیق کریں۔ PIN, MPIN, OTP یا wallet password کبھی ویب سائٹ میں درج نہ کریں۔

## Homepage Owner Photo

اپنی تصویر اس جگہ اپلوڈ کریں:

`htdocs/assets/images/hammad-ali.jpg`

فائل کا نام بالکل `hammad-ali.jpg` رکھیں۔ بہتر تصویر عمودی portrait، کم از کم 800×1000 pixels ہو۔ Homepage خود اسی path سے تصویر دکھائے گا۔ اگر آپ کی تصویر ابھی تیار نہیں تو placeholder text نظر آئے گا۔
