# Changelog

All notable changes to this project will be documented in this file.

## [1.2.1] - 2026-10-23

Patch release adding a Test Connection button to the admin panel.

### Added
- Test Connection button to verify Cloudflare API credentials.
- Success/error messages based on API response.

## [1.2.0] - 2026-10-23

Feature release adding SSL/TLS and HTTP protocol settings management.

### Added
- SSL/TLS mode (Off, Flexible, Full, Full Strict)
- Always Use HTTPS
- Minimum TLS Version
- TLS 1.3 with 0-RTT
- Automatic HTTPS Rewrites
- HTTP/2 and HTTP/3
- 0-RTT Connection Resumption
- Early Hints
- Opportunistic Encryption
- Apply all settings to Cloudflare with one button
- English and Turkish localization

## [1.1.0] - 2026-10-23

Feature release adding Cloudflare zone settings management from the Flarum admin panel.

### Added
- Manage development mode, Bot Fight Mode, Always Online, Brotli, Rocket Loader, Mirage, minification, security level, Polish, and browser cache TTL.
- Apply all saved Cloudflare settings to the zone with one button.
- Purge cache button in the admin panel.
- English and Turkish localization for all new settings.

### Endpoints
- POST /cloudflare/apply - Apply saved settings to Cloudflare (admin only).

## [1.0.1] - 2026-10-23

Patch release to refresh Packagist metadata after the Cloudflare conversion.

### Changed
- Bumped version to 1.0.1 to work around Packagist cached metadata.
- Updated package and lockfile metadata.

## [1.0.0] - 2026-10-23

Initial release of the Cloudflare API integration extension for Flarum 2.0.

### Added
- Cloudflare API integration configurable from the Flarum admin panel.
- Admin settings for enabling the integration, API token, account ID, zone ID, zone name, automatic cache purge, and development mode.
- Backend endpoints to verify the Cloudflare API connection and purge the zone cache.
- Automatic cache purge listener for discussions and posts when enabled.
- English and Turkish localization.

### Requirements
- Flarum 2.0
- Guzzle HTTP client (installed via Composer)