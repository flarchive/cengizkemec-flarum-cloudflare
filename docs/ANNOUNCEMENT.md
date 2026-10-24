# Cloudflare for Flarum – Extension Announcement

Hello Flarum community! 👋

I'm excited to share **Cloudflare for Flarum**, a new extension that brings Cloudflare's powerful CDN, security, and performance features directly to your Flarum forum admin panel.

## Why Cloudflare?

Cloudflare is one of the most popular CDN and security providers on the web. Adding Cloudflare to your Flarum forum gives you:

- **Faster load times** – Global CDN caches your forum pages and assets closer to your visitors.
- **DDoS protection** – Cloudflare absorbs malicious traffic before it reaches your server.
- **SSL/TLS encryption** – Free SSL certificates with flexible encryption modes.
- **Bot protection** – Block automated bots and scrapers with Bot Fight Mode.
- **Image optimization** – Polish and Mirage optimize images for faster delivery.
- **Minification** – Automatically minify JavaScript, CSS, and HTML.
- **Always Online** – Serve cached pages even when your origin server is down.
- **HTTP/2 & HTTP/3** – Modern protocols for faster connections.
- **Early Hints** – Speed up page loads with 103 Early Hints.
- **Brotli compression** – Smaller file sizes for faster transfers.

## What does this extension do?

**Cloudflare for Flarum** lets you manage all of the above directly from the Flarum admin panel — no need to log in to the Cloudflare dashboard for everyday tasks.

### Features

- **Easy setup** – Just enter your API token, account ID, and zone ID.
- **Test Connection** – Verify your credentials with one click.
- **Cache Purge** – Purge the cache manually or automatically when new discussions/posts are created.
- **Zone settings management**:
  - Development mode
  - Bot Fight Mode
  - Always Online
  - Brotli compression
  - Rocket Loader
  - Mirage image optimization
  - Minify JS/CSS/HTML
  - Security level
  - Polish image optimization
  - Browser cache TTL
  - SSL/TLS mode (Off, Flexible, Full, Full Strict)
  - Always Use HTTPS
  - Minimum TLS version
  - TLS 1.3 with 0-RTT
  - Automatic HTTPS Rewrites
  - HTTP/2 and HTTP/3
  - 0-RTT Connection Resumption
  - Early Hints
  - Opportunistic Encryption
- **Apply all settings** – Push all saved settings to Cloudflare with one button.
- **Localization** – English and Turkish.

## Installation

```bash
composer require cengizkemec/cloudflare
```

Then enable the extension in **Admin Panel → Extensions → Cloudflare** and configure your credentials.

## Requirements

- Flarum 2.0
- PHP with Guzzle HTTP client (installed via Composer)
- A Cloudflare account with an API token

## Create a Cloudflare API token

1. Go to Cloudflare Dashboard → **My Profile → API Tokens**.
2. Click **Create Token**.
3. Grant permissions:
   - `Zone: Cache Purge`
   - `Zone: Read`
   - `Zone: Edit`
4. Select your zone (domain).
5. Copy the token and paste it into the Flarum admin settings.

## Links

- **GitHub**: https://github.com/cengizkemec/flarum-cloudflare
- **Packagist**: https://packagist.org/packages/cengizkemec/cloudflare
- **User Guide**: https://github.com/cengizkemec/flarum-cloudflare/blob/main/docs/GUIDE.md

## Feedback

I'd love to hear your feedback, bug reports, and feature requests. Feel free to open an issue on GitHub or reply here!

Thanks for trying Cloudflare for Flarum! 🚀