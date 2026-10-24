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

        $body = $request->getParsedBody();

        if (! is_array($body)) {
            $body = [];
        }

        $files = null;

        if (array_key_exists('files', $body)) {
            if (! is_array($body['files'])) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'The files field must be an array of URLs.',
                ]);
            }

            $files = array_values(array_unique(array_filter(array_map(
                static fn ($file) => is_string($file) ? trim($file) : '',
                $body['files']
            ), static fn (string $file) => $file !== '')));

            if ($files === []) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'No valid file URLs were provided for purging.',
                ]);
            }
        }

        try {
            $result = $this->cloudflare->purgeCache($files ?? []);

            return new JsonResponse([
                'success' => true,
                'result' => $result['result'] ?? null,
            ]);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage(),
            ], 200);
        }
    }
}