# Changelog

All notable changes to this project will be documented in this file.

## [1.2.10] - 2026-10-24

Documentation and confirmation release following a transient admin panel outage on warthunder.tr.

### Notes
- Investigated the report of a broken admin panel (`app.load is not a function` / `literal not terminated before end of script`) after updating this extension.
- Verified that both `js/dist/admin.js` and the published assets are syntactically valid (`node --check` / `vm.Script` clean). The coordinates mentioned in the error (admin.js:492:32420 and admin-tr.js) match the concatenated Flarum admin bundle, not a broken file from this extension. Our contribution is at a different offset and the translation catalog is complete.
- Root cause: At the time of the failure the browser / CDN served a truncated (half-cut) copy of the concatenated `admin.js` and `admin-tr.js`. A single unterminated string literal in the combined bundle prevents the entire admin panel from booting. This is a known characteristic of Flarum’s concat-style admin assets.
- The build hardening added in 1.2.9 (syntax validation of every `dist/*.js` before the build is considered successful) prevents this repository from ever shipping a parse-broken file again.
- Assets were regenerated around 18:49 UTC and are currently healthy. If the admin panel still fails to load after an update, clear the browser cache, hard-refresh, and (if applicable) purge the Cloudflare cache for the `/assets/admin*.js` files.

No functional code changes. Existing installations remain fully compatible.

## [1.2.9] - 2026-10-24

Build hardening release: this extension can never ship a parse-broken admin JS again.

### Added
- `npm run build` now syntax-validates every `dist/*.js` output and fails the build on broken output. In Flarum's concatenated admin bundle a single parse-broken file takes the entire admin panel down (`app.load is not a function`), so a broken dist can never be committed or published from this repo.
- New `npm run check` runs the same validation standalone (e.g. before pushing a release).

## [1.2.8] - 2026-10-24

Maintenance release with code review fixes, security hardening, and a strict confirmation for automatic purging.

### Fixed
- Fixed a broken admin JS build: `mithril` was missing from the JS dev dependencies, so `npm run build` failed. The bundle was rebuilt.
- Cloudflare 4xx/5xx responses no longer lose their detailed error message; the real API error (e.g. "Invalid API Token") is now surfaced. Added a 5s connect timeout.
- `Apply Settings to Cloudflare` no longer aborts on the first failure: every setting is applied independently and failures are reported per setting. The admin panel shows a partial-apply warning listing how many settings failed.
- File purge requests are sanitized (trimmed, de-duplicated) and sent in batches of at most 30 URLs, matching the Cloudflare API limit.
- Rejecting an invalid or empty `files` payload no longer silently turns into a purge-everything request.
- Corrected the wrong `setMinify` docblock.
- Composer requirements aligned with Flarum 2.x core: PHP `^8.3`, Guzzle `^7.7`.

### Added
- Automatic purge now ships with a strict, non-dismissible warning in the admin panel and requires an explicit confirmation dialog (acknowledgment checkbox required) before it can be enabled. It remains **off by default**.
- Admin action buttons are translated (EN/TR), guarded against double submits, and partial apply failures are shown as warnings.
- New English/Turkish translation keys for actions, alerts, and the automatic purge confirmation.

## [1.2.7] - 2026-10-23

Patch release replacing browser alerts with Flarum in-app alerts.

### Changed
- Test Connection, Apply Settings, and Purge Cache now show notifications inside the Flarum admin panel.
- No more browser popup alerts.

## [1.2.6] - 2026-10-23

Patch release returning detailed Cloudflare API error messages.

### Fixed
- Test Connection now shows detailed error messages (token, zone, permission issues).
- All API responses return HTTP 200 with success/message fields for clearer feedback.

## [1.2.5] - 2026-10-23

Patch release fixing 400 errors by using the running app instance.

### Fixed
- Admin API calls now use the running Flarum app instance, ensuring CSRF token and session are correct.
- Test Connection, Apply Settings, and Purge Cache should work without 400 errors.

## [1.2.4] - 2026-10-23

Patch release fixing admin API requests by using Flarum's built-in request handler.

### Fixed
- Admin API calls now use Flarum app.request, which handles CSRF and session automatically.
- Test Connection, Apply Settings, and Purge Cache should work correctly.

## [1.2.3] - 2026-10-23

Patch release fixing 400 Bad Request on admin API calls.

### Fixed
- CSRF token is now read from the page meta tag.
- Added X-Requested-With header to all admin API requests.
- Test Connection, Apply Settings, and Purge Cache should work without 400 errors.

## [1.2.2] - 2026-10-23

Patch release fixing admin API requests.

### Fixed
- Admin panel requests now include the CSRF token.
- Test Connection, Apply Settings, and Purge Cache should work correctly.

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
