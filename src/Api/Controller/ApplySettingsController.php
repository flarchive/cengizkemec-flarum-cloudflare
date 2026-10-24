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

class ApplySettingsController implements RequestHandlerInterface
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
            ['results' => $results, 'errors' => $errors] = $this->cloudflare->applySettings($this->settings);

            return new JsonResponse([
                'success' => $errors === [],
                'results' => $results,
                'errors' => $errors,
                'message' => $errors === [] ? null : reset($errors),
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 200);
        }
    }
}