# Hammad Graphic — Analytics Setup (Urdu)

یہ مفت setup دو حصوں میں کام کرتا ہے:

1. **Cloudflare Web Analytics:** page views، traffic source/referrer، ملک، browser/device اور performance reports Cloudflare dashboard میں۔
2. **Hammad Graphic Admin Analytics:** روزانہ page views، browser-session کے بجائے browser ID کی بنیاد پر unique visitor count، WhatsApp/Contact/Order clicks، گزشتہ 7 دن اور top pages۔

Cloudflare Web Analytics custom button-click events فراہم نہیں کرتا، اس لیے clicks ہماری اپنی PHP/MySQL event table میں محفوظ ہوتے ہیں۔ دونوں حصے مفت ہیں۔

## 1) GitHub سے فائلیں حاصل کریں

Branch: `build/hammad-graphic-agency`

اس branch کی تازہ ZIP ڈاؤن لوڈ کریں:
https://github.com/jabham786-alt/portfolio/archive/refs/heads/build/hammad-graphic-agency.zip

## 2) Database migration

InfinityFree کے phpMyAdmin میں اپنا Hammad Graphic database منتخب کریں۔

- اگر `database/content-migration.sql` ابھی تک نہیں چلائی تو اسے پہلے ایک بار چلائیں۔
- پھر `database/analytics-migration.sql` کھول کر مکمل SQL چلائیں۔
- Analytics migration صرف ایک بار چلائیں۔ اس سے `analytics_events` table بنے گی۔

## 3) Cloudflare Web Analytics token

1. Cloudflare account میں login کریں: https://dash.cloudflare.com/
2. **Web Analytics** کھولیں اور **Add a site** منتخب کریں۔
3. Hostname درج کریں: `hammadgraphic786com.free.je`
4. Site بننے کے بعد **Manage site** میں جا کر JavaScript Beacon کا token حاصل کریں۔
5. اپنی ویب سائٹ کے Admin Panel میں login کریں۔
6. **Website Analytics** کھولیں، Cloudflare Site Token field میں token paste کریں اور **Token محفوظ کریں** دبائیں۔

Token کوئی خفیہ API key نہیں؛ یہ browser میں استعمال ہونے والا public Beacon identifier ہے۔ اسے GitHub یا `includes/config.php` میں ڈالنے کی ضرورت نہیں۔

## 4) InfinityFree پر اپ ڈیٹ

GitHub میں commit ہونے سے InfinityFree hosting خود بخود اپ ڈیٹ نہیں ہوتی۔ ZIP extract کریں اور نئی/بدلی ہوئی فائلیں InfinityFree File Manager کے ذریعے اپنی website کے `htdocs` میں upload/replace کریں۔ اپنے live `includes/config.php` کو overwrite نہ کریں۔

اہم فائلیں:
- `analytics-event.php`
- `includes/analytics-snippet.php`
- `assets/js/analytics.js`
- `admin/analytics.php`
- `admin/index.php`
- `index.php`, `order.php`, `zoom-meeting.php`, `payment.php`
- `database/analytics-migration.sql`

## 5) ٹیسٹ

- اپنی live ویب سائٹ نئی private/incognito window میں کھولیں۔
- Home، Order، Zoom یا Payment page پر جائیں۔
- WhatsApp، Contact یا Start a project/Order بٹن آزمائیں۔
- Admin Panel > Website Analytics میں local counts دیکھیں۔
- Cloudflare dashboard میں reports ظاہر ہونے میں کچھ منٹ لگ سکتے ہیں۔

## Privacy

یہ setup visitor کا نام، email، phone، IP address یا مکمل referrer اپنے database میں محفوظ نہیں کرتا۔ Browser میں random visitor identifier رکھا جاتا ہے اور server پر اس کا SHA-256 hash محفوظ ہوتا ہے۔ Cloudflare اپنی documentation کے مطابق Web Analytics میں visitor personal data collect/use نہیں کرتا۔ Ad blockers بعض اوقات Cloudflare Beacon روک سکتے ہیں؛ ایسی صورت میں Cloudflare counts کم ہو سکتے ہیں۔

**نوٹ:** ابھی GitHub code تیار ہے، مگر InfinityFree پر upload، database migration اور live end-to-end test کیے بغیر اسے live فعال تصور نہ کریں۔
