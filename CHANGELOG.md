# Changelog

All notable changes to this project will be documented in this file.

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