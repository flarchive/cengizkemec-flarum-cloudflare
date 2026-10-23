# Changelog

All notable changes to this project will be documented in this file.

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