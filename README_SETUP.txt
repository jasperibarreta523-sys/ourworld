OUR LITTLE WORLD — ONLINE, PASSWORD-PROTECTED WEBSITE
====================================================

WHAT THIS VERSION DOES
- Password-protected login page.
- Private photo and video galleries.
- Upload and delete memories after login.
- Relationship timeline with dates and notes.
- Romantic pink design for phones and computers.
- Media files are delivered through a PHP endpoint that checks the login session.
- The data/ and uploads/ folders have Apache access restrictions via .htaccess.

IMPORTANT: THIS ZIP IS THE WEBSITE FILES, NOT A HOSTED WEBSITE
A shareable online link requires a web host that supports PHP 8+ and HTTPS. This package does not create a live URL automatically.

STEP 1 — CHANGE THE PASSWORD
1. Open config.php in a text editor.
2. Replace:
   CHANGE-THIS-TO-A-LONG-UNIQUE-PASSWORD
   with a long, unique password that you do not use elsewhere.
3. You can also customize SITE_TITLE and SITE_MESSAGE.
4. Save config.php.
Do not send the password in public messages or post it beside the link.

STEP 2 — UPLOAD TO A PHP WEB HOST
1. Choose a PHP 8+ hosting provider that supports HTTPS and file uploads.
2. In its file manager, open the web root, commonly public_html.
3. Upload and extract the contents of this ZIP so index.php is directly inside the web root.
4. Confirm that data/ and uploads/ folders exist beside index.php.
5. If needed, set data/ and uploads/ writable by the PHP process. Use the hosting provider's recommended permissions; do not set folders to 777 unless your host specifically instructs you.
6. Turn on HTTPS/SSL in the hosting dashboard.
7. Visit your domain. The login page should appear.
8. Enter the password you set in config.php.

STEP 3 — SHARE IT
Send your girlfriend the website URL and give her the password privately. She can open it from a phone or computer. The site must stay hosted and renewed to remain online.

UPLOAD LIMITS
- Each file is limited to 50 MB by this website.
- Your host may impose a smaller PHP upload_max_filesize or post_max_size. If uploads fail, ask the host how to increase these limits.
- The site accepts JPG, PNG, WEBP, GIF, MP4, WEBM, and MOV files.
- Hosting storage and bandwidth limits apply, especially to videos.

PRIVACY AND SECURITY NOTES
- This is a simple password gate for a personal gallery, not an audited security product.
- Use HTTPS and a strong unique password. Keep PHP and the hosting account updated.
- The host administrator can access the server files. Anyone you share the password with can view the memories.
- Configure your host so .htaccess rules are honored (typically Apache). If your host uses Nginx, ask the host to configure equivalent deny rules for /data and /uploads; .htaccess files are ignored by Nginx.
- Test that visiting https://your-domain.example/uploads/ and /data/ does not expose a directory listing or file contents.
- Keep a backup of your originals. Do not upload private material unless everyone pictured agrees to share it.
- The site uses Google Fonts and an Unsplash hero background, so the fonts/banner may need internet access. Your uploaded media is stored on your hosting account, not in a public photo service.
- This starter does not include password reset, per-user accounts, brute-force rate limiting, or end-to-end encryption. For sensitive material, use a professionally managed private gallery.

LOCAL TESTING (OPTIONAL)
To test on your own computer, install XAMPP, copy the extracted folder into htdocs, edit config.php, start Apache, then visit:
http://localhost/our_little_world_online/
Do not use this local URL to share with your girlfriend; it only works on your computer/network.

TROUBLESHOOTING
- Blank page / PHP error: confirm the host uses PHP 8+ and check its PHP error log.
- Upload errors: check host upload limits and folder permissions.
- Uploaded files show 403: your host may be denying PHP access or not honoring .htaccess; configure the host's private folder rules.
- Site won't load over HTTPS: enable the host's SSL certificate.
