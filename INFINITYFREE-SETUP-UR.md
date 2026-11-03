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

ایک Test Order جمع کریں، Admin Panel میں اسے کھولیں، Status کو Confirmed کریں، اور اگر فائل منسلک کی ہو تو اس کا Download بھی ٹیسٹ کریں۔

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
