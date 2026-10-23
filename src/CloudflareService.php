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
    public function setDevelopmentMode(bool $enabled): array
    {
        $zoneId = $this->getZoneId();

        if ($zoneId === '') {
            throw new \RuntimeException('Cloudflare zone ID is not configured.');
        }

        return $this->request('PATCH', 'zones/' . rawurlencode($zoneId) . '/settings/development_mode', [
            'value' => $enabled ? 'on' : 'off',
        ]);
    }
}