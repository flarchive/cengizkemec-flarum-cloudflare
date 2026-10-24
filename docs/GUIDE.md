# Cloudflare for Flarum – User Guide

This guide explains how to install, configure, and use the Cloudflare extension for Flarum 2.0.

## 1. Installation

```bash
composer require cengizkemec/cloudflare
```

Then enable the extension in the Flarum admin panel:

1. Go to **Admin Panel → Extensions**.
2. Find **Cloudflare** and click **Enable**.

## 2. Create a Cloudflare API token

1. Log in to the Cloudflare dashboard.
2. Go to **My Profile → API Tokens**.
3. Click **Create Token**.
4. Use the **Edit zone DNS** template or create a custom token.
5. Grant at least these permissions:
   - `Zone: Cache Purge`
   - `Zone: Read`
   - `Zone: Edit` (required for zone settings)
6. Select the zone you want to manage.
7. Create the token and copy it.

## 3. Configure the extension

Open **Admin Panel → Extensions → Cloudflare → Settings** and fill in:

- **Enable Cloudflare integration**: Turn on.
- **API Token**: Paste the token you created.
- **Account ID**: Your Cloudflare account ID.
- **Zone ID**: The zone ID of your domain.
- **Zone name**: Your domain name (for reference).

Then configure the optional features:

- **Purge cache automatically**: Purge cache on new discussions/posts.

  > **WARNING — high-impact setting.** When enabled, every new discussion or post
  > clears the **entire** Cloudflare cache. On an active forum this nearly disables
  > caching and slows down posting. The admin panel shows a strict warning and asks
  > for explicit confirmation before this can be enabled; it ships **off** by default.

- **Development mode**: Enable Cloudflare development mode.
- **Bot Fight Mode**: Block automated traffic.
- **Always Online**: Serve cached pages when the origin is down.
- **Brotli**: Enable Brotli compression.
- **Rocket Loader**: Optimize JavaScript loading.
- **Mirage**: Optimize images for mobile.
- **Minify JavaScript/CSS/HTML**: Reduce file sizes.
- **Security Level**: Choose the zone security level.
- **Polish**: Image optimization (Off, Lossless, Lossy).
- **Browser Cache TTL**: Browser caching duration.
- **Purge cache automatically**: Purge cache on new discussions/posts.
- **SSL/TLS mode**: Off, Flexible, Full, or Full (Strict).
- **Always Use HTTPS**: Redirect HTTP to HTTPS.
- **Minimum TLS Version**: Minimum TLS version.
- **TLS 1.3**: Enable TLS 1.3.
- **Automatic HTTPS Rewrites**: Rewrite HTTP URLs to HTTPS.
- **HTTP/2 and HTTP/3**: Enable modern protocols.
- **0-RTT**: Enable 0-RTT connection resumption.
- **Early Hints**: Enable 103 Early Hints.
- **Opportunistic Encryption**: Enable opportunistic encryption.

## 4. Apply settings and purge cache

- Click **Apply Settings to Cloudflare** to push the saved settings to your zone.
- Click **Purge Cache** to clear the cache immediately.

## 5. API endpoints

All endpoints require an admin user.

- `POST /cloudflare/test` – Verify the API token and zone.
- `POST /cloudflare/purge` – Purge the cache (optionally for specific files).
- `POST /cloudflare/apply` – Apply all saved settings to Cloudflare.

## 6. Troubleshooting

- **Invalid API token**: Check token permissions and zone selection.
- **Zone not found**: Verify Zone ID and Account ID.
- **Settings not applied**: Click **Apply Settings to Cloudflare** after saving.
- **Cache not purged**: Check token has `Zone: Cache Purge` permission.

## 7. Turkish guide (Türkçe rehber)

### Kurulum

```bash
composer require cengizkemec/cloudflare
```

Admin Panel → Eklentiler → Cloudflare → Etkinleştir.

### Cloudflare API token oluşturma

1. Cloudflare dashboard'a giriş yap.
2. My Profile → API Tokens.
3. Create Token.
4. En az şu izinleri ver:
   - `Zone: Cache Purge`
   - `Zone: Read`
   - `Zone: Edit`
5. Token'ı kopyala.

### Ayarlar

Admin Panel → Eklentiler → Cloudflare → Ayarlar:

- Entegrasyonu etkinleştir
- API Token
- Account ID
- Zone ID
- Zone adı
- Geliştirme modu
- Bot Fight Mode
- Always Online
- Brotli
- Rocket Loader
- Mirage
- Minify ayarları
- Security Level
- Polish
- Browser Cache TTL
- Otomatik purge

  > **UYARI — yüksek etkili bir ayardır.** Açıldığında her yeni tartışma veya ileti
  > Cloudflare önbelleğinin **tamamını** temizler; aktif forumlarda önbelleğe alma
  > neredeyse devre dışı kalır ve ileti göndermek yavaşlar. Yönetim paneli, açılmadan
  > önce sıkı bir uyarı ve açık onay ister; varsayılan olarak **kapalı** gelir.
- SSL/TLS modu
- Always Use HTTPS
- Minimum TLS sürümü
- TLS 1.3
- Otomatik HTTPS yeniden yazma
- HTTP/2 ve HTTP/3
- 0-RTT
- Early Hints
- Fırsatçı şifreleme

### Uygulama

- **Apply Settings to Cloudflare** ile ayarları zone'a gönder.
- **Purge Cache** ile önbelleği temizle.

### Sorun giderme

- Token geçersizse izinleri kontrol et.
- Zone bulunamıyorsa Zone ID ve Account ID'yi kontrol et.
- Ayarlar uygulanmıyorsa önce kaydet, sonra Apply butonuna bas.