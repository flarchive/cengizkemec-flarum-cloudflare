<?php

declare(strict_types=1);

namespace CengizKemec\Cloudflare\Api\Controller;

use CengizKemec\Cloudflare\CloudflareService;
use Flarum\Http\RequestUtil;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PurgeCacheController implements RequestHandlerInterface
{
    public function __construct(private CloudflareService $cloudflare)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $actor->assertAdmin();

        $body = json_decode((string) $request->getBody(), true);
        $files = [];

        if (is_array($body) && isset($body['files']) && is_array($body['files'])) {
            $files = array_values(array_filter($body['files'], 'is_string'));
        }

        try {
            $result = $this->cloudflare->purgeCache($files);

            return new JsonResponse([
                'success' => true,
                'result' => $result['result'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}