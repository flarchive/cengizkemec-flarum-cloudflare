# Cloudflare for Flarum

Cloudflare API integration for Flarum 2.0. Configure your Cloudflare zone settings, purge the cache, and manage common Cloudflare features directly from the Flarum admin panel.

## Features

- Enable or disable Cloudflare integration
- Configure API token, account ID, zone ID, and zone name
- Verify the Cloudflare API connection
- Purge the Cloudflare cache manually
- Automatically purge the cache when discussions or posts are created
- Toggle development mode
- Toggle Bot Fight Mode
- Toggle Always Online
- Toggle Brotli compression
- Toggle Rocket Loader
- Toggle Mirage
- Minify JavaScript, CSS, and HTML
- Set security level
- Set Polish image optimization
- Set browser cache TTL
- Apply all saved settings to Cloudflare with one click
- English and Turkish localization

## Requirements

- Flarum 2.0
- PHP with Guzzle HTTP client (installed via Composer)

## Installation

```bash
composer require cengizkemec/cloudflare
```

Then enable the extension in the Flarum admin panel and configure your Cloudflare credentials.

## Cloudflare API token permissions

Create an API token in Cloudflare with at least:

- `Zone:Cache Purge`
- `Zone:Read`
- `Zone:Edit` (for zone settings)

## Admin settings

Open **Admin Panel → Extensions → Cloudflare → Settings** and configure:

- Enable Cloudflare integration
- API Token
- Account ID
- Zone ID
- Zone name
- Development mode
- Bot Fight Mode
- Always Online
- Brotli
- Rocket Loader
- Mirage
- Minify JavaScript
- Minify CSS
- Minify HTML
- Security Level
- Polish
- Browser Cache TTL
- Purge cache automatically

Use **Apply Settings to Cloudflare** to push the saved settings to your zone, and **Purge Cache** to clear the cache immediately.

## Documentation

See the full user guide: [docs/GUIDE.md](docs/GUIDE.md)

## API endpoints

All endpoints require an admin user.

- `POST /cloudflare/test` - Verify the Cloudflare API token and zone configuration.
- `POST /cloudflare/purge` - Purge the zone cache, optionally for specific files.
- `POST /cloudflare/apply` - Apply all saved zone settings to Cloudflare.