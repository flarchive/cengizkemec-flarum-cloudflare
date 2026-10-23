<?php

declare(strict_types=1);

namespace CengizKemec\Cloudflare;

use Flarum\Settings\SettingsRepositoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CloudflareService
{
    private Client $client;

    public function __construct(private SettingsRepositoryInterface $settings)
    {
        $this->client = new Client([
            'base_uri' => 'https://api.cloudflare.com/client/v4/',
            'timeout' => 20,
        ]);
    }

    public function isEnabled(): bool
    {
        return (bool) $this->settings->get('cloudflare.enabled', false);
    }

    public function getApiToken(): string
    {
        return trim((string) $this->settings->get('cloudflare.api_token', ''));
    }

    public function getAccountId(): string
    {
        return trim((string) $this->settings->get('cloudflare.account_id', ''));
    }

    public function getZoneId(): string
    {
        return trim((string) $this->settings->get('cloudflare.zone_id', ''));
    }

    public function getZoneName(): string
    {
        return trim((string) $this->settings->get('cloudflare.zone_name', ''));
    }

    public function isAutoPurgeEnabled(): bool
    {
        return $this->isEnabled() && (bool) $this->settings->get('cloudflare.auto_purge', false);
    }

    public function isDevelopmentModeEnabled(): bool
    {
        return (bool) $this->settings->get('cloudflare.development_mode', false);
    }

    /**
     * @throws \RuntimeException
     */
    public function request(string $method, string $path, array $json = []): array
    {
        $token = $this->getApiToken();

        if ($token === '') {
            throw new \RuntimeException('Cloudflare API token is not configured.');
        }

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ];

        if (! empty($json)) {
            $options['json'] = $json;
        }

        try {
            $response = $this->client->request($method, ltrim($path, '/'), $options);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('Cloudflare request failed: ' . $e->getMessage(), 0, $e);
        }

        $decoded = json_decode((string) $response->getBody(), true);

        if (! is_array($decoded)) {
            throw new \RuntimeException('Invalid response from Cloudflare.');
        }

        if (($decoded['success'] ?? false) !== true) {
            $errors = $decoded['errors'] ?? [];
            $message = is_array($errors) && isset($errors[0]['message'])
                ? (string) $errors[0]['message']
                : 'Cloudflare API request failed.';

            throw new \RuntimeException($message);
        }

        return $decoded;
    }

    /**
     * @throws \RuntimeException
     */
    public function verifyToken(): array
    {
        return $this->request('GET', 'user/tokens/verify');
    }

    /**
     * @throws \RuntimeException
     */
    public function getZone(): array
    {
        $zoneId = $this->getZoneId();

        if ($zoneId === '') {
            throw new \RuntimeException('Cloudflare zone ID is not configured.');
        }

        return $this->request('GET', 'zones/' . rawurlencode($zoneId));
    }

    /**
     * @param string[] $files
     * @throws \RuntimeException
     */
    public function purgeCache(array $files = []): array
    {
        $zoneId = $this->getZoneId();

        if ($zoneId === '') {
            throw new \RuntimeException('Cloudflare zone ID is not configured.');
        }

        $payload = empty($files)
            ? ['purge_everything' => true]
            : ['files' => array_values($files)];

        return $this->request('POST', 'zones/' . rawurlencode($zoneId) . '/purge_cache', $payload);
    }

    /**
     * @throws \RuntimeException
     */
    public function setSetting(string $id, mixed $value): array
    {
        $zoneId = $this->getZoneId();

        if ($zoneId === '') {
            throw new \RuntimeException('Cloudflare zone ID is not configured.');
        }

        return $this->request('PATCH', 'zones/' . rawurlencode($zoneId) . '/settings/' . rawurlencode($id), [
            'value' => $value,
        ]);
    }

    public function setDevelopmentMode(bool $enabled): array
    {
        return $this->setSetting('development_mode', $enabled ? 'on' : 'off');
    }

    public function setBotFightMode(bool $enabled): array
    {
        return $this->setSetting('bot_fight_mode', $enabled ? 'on' : 'off');
    }

    public function setAlwaysOnline(bool $enabled): array
    {
        return $this->setSetting('always_online', $enabled ? 'on' : 'off');
    }

    public function setBrotli(bool $enabled): array
    {
        return $this->setSetting('brotli', $enabled ? 'on' : 'off');
    }

    public function setRocketLoader(bool $enabled): array
    {
        return $this->setSetting('rocket_loader', $enabled ? 'on' : 'off');
    }

    public function setMirage(bool $enabled): array
    {
        return $this->setSetting('mirage', $enabled ? 'on' : 'off');
    }

    public function setSecurityLevel(string $level): array
    {
        return $this->setSetting('security_level', $level);
    }

    public function setPolish(string $mode): array
    {
        return $this->setSetting('polish', $mode);
    }

    public function setBrowserCacheTTL(int $seconds): array
    {
        return $this->setSetting('browser_cache_ttl', $seconds);
    }

    /**
     * @return array{js: array, css: array, html: array}
     */
    public function setMinify(bool $js, bool $css, bool $html): array
    {
        return $this->setSetting('minify', [
            'js' => $js ? 'on' : 'off',
            'css' => $css ? 'on' : 'off',
            'html' => $html ? 'on' : 'off',
        ]);
    }

    /**
     * Apply all configured settings to Cloudflare.
     *
     * @return array<string, array|string>
     */
    public function applySettings(\Flarum\Settings\SettingsRepositoryInterface $settings): array
    {
        $results = [];

        $results['development_mode'] = $this->setDevelopmentMode((bool) $settings->get('cloudflare.development_mode', false));
        $results['bot_fight_mode'] = $this->setBotFightMode((bool) $settings->get('cloudflare.bot_fight_mode', false));
        $results['always_online'] = $this->setAlwaysOnline((bool) $settings->get('cloudflare.always_online', false));
        $results['brotli'] = $this->setBrotli((bool) $settings->get('cloudflare.brotli', false));
        $results['rocket_loader'] = $this->setRocketLoader((bool) $settings->get('cloudflare.rocket_loader', false));
        $results['mirage'] = $this->setMirage((bool) $settings->get('cloudflare.mirage', false));
        $results['security_level'] = $this->setSecurityLevel((string) $settings->get('cloudflare.security_level', 'medium'));
        $results['polish'] = $this->setPolish((string) $settings->get('cloudflare.polish', 'off'));
        $results['browser_cache_ttl'] = $this->setBrowserCacheTTL((int) $settings->get('cloudflare.browser_cache_ttl', 14400));
        $results['minify'] = $this->setMinify(
            (bool) $settings->get('cloudflare.minify_js', false),
            (bool) $settings->get('cloudflare.minify_css', false),
            (bool) $settings->get('cloudflare.minify_html', false)
        );

        $results['ssl'] = $this->setSetting('ssl', (string) $settings->get('cloudflare.ssl_mode', 'flexible'));
        $results['always_use_https'] = $this->setSetting('always_use_https', (bool) $settings->get('cloudflare.always_use_https', false) ? 'on' : 'off');
        $results['min_tls_version'] = $this->setSetting('min_tls_version', (string) $settings->get('cloudflare.min_tls_version', '1.2'));
        $results['tls_1_3'] = $this->setSetting('tls_1_3', (string) $settings->get('cloudflare.tls_1_3', 'on'));
        $results['automatic_https_rewrites'] = $this->setSetting('automatic_https_rewrites', (bool) $settings->get('cloudflare.automatic_https_rewrites', false) ? 'on' : 'off');
        $results['http2'] = $this->setSetting('http2', (bool) $settings->get('cloudflare.http2', false) ? 'on' : 'off');
        $results['http3'] = $this->setSetting('http3', (bool) $settings->get('cloudflare.http3', false) ? 'on' : 'off');
        $results['0rtt'] = $this->setSetting('0rtt', (bool) $settings->get('cloudflare.0rtt', false) ? 'on' : 'off');
        $results['early_hints'] = $this->setSetting('early_hints', (bool) $settings->get('cloudflare.early_hints', false) ? 'on' : 'off');
        $results['opportunistic_encryption'] = $this->setSetting('opportunistic_encryption', (bool) $settings->get('cloudflare.opportunistic_encryption', false) ? 'on' : 'off');

        return $results;
    }
}