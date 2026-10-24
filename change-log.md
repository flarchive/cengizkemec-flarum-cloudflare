# Cloudflare for Flarum – Change Log

Forum-ready release notes. **Newest entry at the top.**

---

## v1.2.8 – 2026-10-24

Hello everyone! 👋

**Cloudflare for Flarum v1.2.8** is out. This is a maintenance release focused on a full code review pass, error-handling fixes, and safety improvements around automatic cache purging.

### What's new

**Better error messages**
- Cloudflare API errors (4xx/5xx) now show their **real messages** in the admin panel (e.g. "Invalid API Token") instead of generic request failures.
- **Apply Settings** no longer stops at the first failure: every setting is applied independently, and any failures are reported **per setting** with a partial-apply warning.

**Safer & smarter cache purging**
- File purge requests are sanitized and automatically split into batches of **max 30 URLs**, matching the Cloudflare API limit.
- An invalid or empty `files` payload no longer silently triggers a *purge-everything* request.

**Automatic purge: strict confirmation** ⚠️
- **Purge cache automatically** (purges on every new discussion/post) stays **off by default** — but it now comes with a **strict, non-dismissible warning** and a **confirmation dialog** (with a mandatory acknowledgment checkbox) that must be completed before it can be enabled.
- Reminder: on an active forum, automatic purging nearly disables Cloudflare caching and slows down posting. Prefer the manual **Purge Cache** button.

**Other fixes**
- Fixed a broken admin JS build (`mithril` was missing from dev dependencies).
- Added a 5s connect timeout to Cloudflare API calls.
- Composer requirements aligned with Flarum 2.x core: **PHP ^8.3**, Guzzle **^7.7**.
- Admin action buttons are now translated (EN/TR) and guarded against double submits.

### Updating

```bash
composer update cengizkemec/cloudflare
```

Then clear the cache: `php flarum cache:clear`. No configuration changes are needed when upgrading from 1.2.x.

### Requirements

- Flarum 2.0
- PHP 8.3+

### Links

- **GitHub**: https://github.com/cengizkemec/flarum-cloudflare
- **Release notes**: https://github.com/cengizkemec/flarum-cloudflare/releases/tag/v1.2.8
- **User Guide**: https://github.com/cengizkemec/flarum-cloudflare/blob/main/docs/GUIDE.md

Feedback, bug reports, and feature requests are welcome — open an issue on GitHub or reply here. Thanks for using Cloudflare for Flarum! 🚀

---
