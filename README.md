# Cloudflare for Flarum

Cloudflare API integration for Flarum 2.0. Configure your Cloudflare zone settings, purge the cache, and manage common Cloudflare features directly from the Flarum admin panel.

## Features

- Enable or disable Cloudflare integration
- Configure API token, account ID, zone ID, and zone name
- Verify the Cloudflare API connection
- Purge the Cloudflare cache manually (bulk file purges are sent in batches of 30, matching the Cloudflare API limit)
- Automatically purge the cache when discussions or posts are created — **off by default**, with a strict warning and a required confirmation before it can be enabled
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
- Apply all saved settings to Cloudflare with one click — each setting is applied independently, so one failure does not block the rest, and failed settings are reported individually
- Detailed Cloudflare API error messages in the admin panel
- English and Turkish localization

## Requirements

- Flarum 2.0
- PHP 8.3+
- Guzzle HTTP client (installed via Composer)

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

### Automatic purge warning

**Purge cache automatically** is disabled by default. When enabled, every new discussion or post sends a *purge-everything* request to Cloudflare, which on an active forum nearly disables caching and makes posting slower. The admin panel shows a strict warning and opens a confirmation dialog that must be acknowledged before this setting can be turned on. Prefer the manual **Purge Cache** button unless you fully understand the impact.

## Documentation

See the full user guide: [docs/GUIDE.md](docs/GUIDE.md)

## API endpoints

All endpoints require an admin user.

- `POST /cloudflare/test` - Verify the Cloudflare API token and zone configuration.
- `POST /cloudflare/purge` - Purge the zone cache, optionally for specific files. Invalid or empty `files` arrays are rejected instead of triggering a purge of everything.
- `POST /cloudflare/apply` - Apply all saved zone settings to Cloudflare. Each setting is applied independently and any failures are reported per setting in the `errors` field.