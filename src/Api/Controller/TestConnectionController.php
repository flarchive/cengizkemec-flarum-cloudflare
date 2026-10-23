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

        try {
            $token = $this->cloudflare->verifyToken();
            $zone = null;

            if ($this->cloudflare->getZoneId() !== '') {
                $zone = $this->cloudflare->getZone();
            }

            return new JsonResponse([
                'success' => true,
                'token' => $token['result'] ?? null,
                'zone' => $zone['result'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}