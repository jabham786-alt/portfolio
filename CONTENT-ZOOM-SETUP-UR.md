# Hammad Graphic — Portfolio & Zoom setup (Urdu)

## New features
- Admin > Website editor: update the homepage heading, introduction, About text, contact email and upload your profile portrait.
- Admin > Portfolio manager: add image, video, audio or external demo link.
- New entries default to **Draft**. Draft items are private and do not appear on the public homepage.
- Use **Publish** only after checking the title, media, description and customer permission. Published work appears in the homepage portfolio section.
- Use **Unpublish** to hide an item without deleting it.
- Admin > Zoom requests: review client enquiries, set the request status and paste the Zoom link after you create the meeting in your own Zoom account.
- Public website > Zoom meeting: clients submit their name, email, WhatsApp, project topic and optional preferred time.

## Database migration
1. Back up your database first.
2. Open InfinityFree Control Panel > phpMyAdmin and select your Hammad Graphic database.
3. Run `database/content-migration.sql` once.
4. If the previous wallet payment migration has not yet been run, run `database/payment-migration.sql` separately once. Do not run either migration twice. If payment columns/tables already exist, do not rerun the payment migration.
5. Do not delete existing orders or tables.

## Upload changed files
Upload the full branch ZIP contents, preserving folder structure, to the document root for your domain (often `htdocs`):
- `index.php`
- `zoom-meeting.php`
- `admin/index.php`
- `admin/site-editor.php`
- `admin/portfolio.php`
- `admin/zoom-meetings.php`
- `database/content-migration.sql`
- `assets/uploads/portfolio/.htaccess`
- `assets/uploads/site/.htaccess`

Also keep all existing files and folders from the repository, especially `includes`, `assets/css`, `assets/js`, `storage`, and the existing order/payment/admin files. Do not overwrite your live `includes/config.php` with a public template unless you have first preserved the correct private database password and INSTALL_KEY.

## Use the new admin tools
1. Sign in at `/admin/login.php`.
2. Open **Portfolio manager**.
3. Add title, category and description. Choose Image, Video, Audio or External demo link.
4. For uploaded media, choose the correct type and select a file. Current application limit is 20 MB per file; the hosting PHP upload limit may be lower.
5. Leave Visibility set to **Draft** and save.
6. Preview/check the work. Then use **Publish** in the saved work list. Only published items appear publicly.
7. For a Zoom enquiry, open **Zoom requests** in admin. Contact the client, create the meeting in your Zoom account, set status to Scheduled and paste the Zoom URL. The client-facing form is a request, not instant booking.

## Privacy and hosting notes
- Never publish payment receipts, transaction/reference IDs, private customer contact details, or a client project without permission.
- Customer names are optional portfolio labels; use a generic label if the customer has not consented.
- The Zoom workflow does not use Zoom API credentials and does not automatically create a Zoom meeting. You create the meeting in Zoom and paste its invitation URL in admin.
- Video/audio upload depends on InfinityFree's PHP upload and storage limits. If a large file is rejected, use a compressed file or a trusted external demo link.
- GitHub changes are not automatically deployed to InfinityFree. Upload the branch files and run the migration before testing the live site.

## Website editor and profile photo
1. Open **Website editor** from the admin dashboard.
2. Change the homepage heading, introduction, About text or contact email.
3. Upload a JPG, PNG or WEBP profile photo (maximum 5 MB).
4. Save. The new portrait replaces the image slots on the homepage.
5. Portfolio items still follow the separate Draft → Publish process.
