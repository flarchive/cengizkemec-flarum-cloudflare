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
            'connect_timeout' => 5,
            // Do not throw on 4xx/5xx so Cloudflare's JSON error body can be decoded.
            'http_errors' => false,
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
            throw new \RuntimeException('Invalid response from Cloudflare (HTTP ' . $response->getStatusCode() . ').');
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
     * Purge the zone cache. Without arguments, purges everything. Otherwise,
     * purges the given file URLs in batches of at most 30 (Cloudflare API limit).
     *
     * @param string[] $files
     * @throws \RuntimeException
     */
    public function purgeCache(array $files = []): array
    {
        $zoneId = $this->getZoneId();

        if ($zoneId === '') {
            throw new \RuntimeException('Cloudflare zone ID is not configured.');
        }

        $path = 'zones/' . rawurlencode($zoneId) . '/purge_cache';

        if (empty($files)) {
            return $this->request('POST', $path, ['purge_everything' => true]);
        }

        $files = array_values(array_unique(array_filter(array_map(
            static fn ($file) => is_string($file) ? trim($file) : '',
            $files
        ), static fn (string $file) => $file !== '')));

        if ($files === []) {
            throw new \RuntimeException('No valid file URLs were provided for purging.');
        }

        $result = [];

        foreach (array_chunk($files, 30) as $chunk) {
            $result = $this->request('POST', $path, ['files' => $chunk]);
        }

        return $result;
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
     * @throws \RuntimeException
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
     * Each setting is applied independently: a failure in one setting does not
     * prevent the remaining settings from being applied.
     *
     * @return array{results: array<string, mixed>, errors: array<string, string>}
     */
    public function applySettings(SettingsRepositoryInterface $settings): array
    {
        $results = [];
        $errors = [];

        $apply = function (string $key, \Closure $action) use (&$results, &$errors): void {
            try {
                $results[$key] = $action();
            } catch (\Throwable $e) {
                $errors[$key] = $e->getMessage();
            }
        };

        $apply('development_mode', fn (): array => $this->setDevelopmentMode((bool) $settings->get('cloudflare.development_mode', false)));
        $apply('bot_fight_mode', fn (): array => $this->setBotFightMode((bool) $settings->get('cloudflare.bot_fight_mode', false)));
        $apply('always_online', fn (): array => $this->setAlwaysOnline((bool) $settings->get('cloudflare.always_online', false)));
        $apply('brotli', fn (): array => $this->setBrotli((bool) $settings->get('cloudflare.brotli', false)));
        $apply('rocket_loader', fn (): array => $this->setRocketLoader((bool) $settings->get('cloudflare.rocket_loader', false)));
        $apply('mirage', fn (): array => $this->setMirage((bool) $settings->get('cloudflare.mirage', false)));
        $apply('security_level', fn (): array => $this->setSecurityLevel((string) $settings->get('cloudflare.security_level', 'medium')));
        $apply('polish', fn (): array => $this->setPolish((string) $settings->get('cloudflare.polish', 'off')));
        $apply('browser_cache_ttl', fn (): array => $this->setBrowserCacheTTL((int) $settings->get('cloudflare.browser_cache_ttl', 14400)));
        $apply('minify', fn (): array => $this->setMinify(
            (bool) $settings->get('cloudflare.minify_js', false),
            (bool) $settings->get('cloudflare.minify_css', false),
            (bool) $settings->get('cloudflare.minify_html', false)
        ));

        $apply('ssl', fn (): array => $this->setSetting('ssl', (string) $settings->get('cloudflare.ssl_mode', 'flexible')));
        $apply('always_use_https', fn (): array => $this->setSetting('always_use_https', (bool) $settings->get('cloudflare.always_use_https', false) ? 'on' : 'off'));
        $apply('min_tls_version', fn (): array => $this->setSetting('min_tls_version', (string) $settings->get('cloudflare.min_tls_version', '1.2')));
        $apply('tls_1_3', fn (): array => $this->setSetting('tls_1_3', (string) $settings->get('cloudflare.tls_1_3', 'on')));
        $apply('automatic_https_rewrites', fn (): array => $this->setSetting('automatic_https_rewrites', (bool) $settings->get('cloudflare.automatic_https_rewrites', false) ? 'on' : 'off'));
        $apply('http2', fn (): array => $this->setSetting('http2', (bool) $settings->get('cloudflare.http2', false) ? 'on' : 'off'));
        $apply('http3', fn (): array => $this->setSetting('http3', (bool) $settings->get('cloudflare.http3', false) ? 'on' : 'off'));
        $apply('0rtt', fn (): array => $this->setSetting('0rtt', (bool) $settings->get('cloudflare.0rtt', false) ? 'on' : 'off'));
        $apply('early_hints', fn (): array => $this->setSetting('early_hints', (bool) $settings->get('cloudflare.early_hints', false) ? 'on' : 'off'));
        $apply('opportunistic_encryption', fn (): array => $this->setSetting('opportunistic_encryption', (bool) $settings->get('cloudflare.opportunistic_encryption', false) ? 'on' : 'off'));

        return ['results' => $results, 'errors' => $errors];
    }
}