<?php

declare(strict_types=1);

namespace CengizKemec\Cloudflare\Api\Controller;

use CengizKemec\Cloudflare\CloudflareService;
use Flarum\Http\RequestUtil;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TestConnectionController implements RequestHandlerInterface
{
    public function __construct(
        private CloudflareService $cloudflare,
        private SettingsRepositoryInterface $settings
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertAdmin();

        $result = [
            'success' => false,
            'token' => null,
            'zone' => null,
            'message' => null,
        ];

        try {
            $token = $this->cloudflare->verifyToken();
            $result['token'] = $token['result'] ?? null;
            $result['success'] = true;
        } catch (\Throwable $e) {
            $result['message'] = 'Token verification failed: ' . $e->getMessage();
            return new JsonResponse($result, 200);
        }

        if ($this->cloudflare->getZoneId() !== '') {
            try {
                $zone = $this->cloudflare->getZone();
                $result['zone'] = $zone['result'] ?? null;
            } catch (\Throwable $e) {
                $result['success'] = false;
                $result['message'] = 'Zone lookup failed: ' . $e->getMessage();
                return new JsonResponse($result, 200);
            }
        }

        if ($result['message'] === null) {
            $result['message'] = 'Cloudflare connection successful.';
        }

        return new JsonResponse($result, 200);
    }
}