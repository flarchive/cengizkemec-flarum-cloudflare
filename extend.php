<?php

use CengizKemec\Cloudflare\Api\Controller\PurgeCacheController;
use CengizKemec\Cloudflare\Api\Controller\TestConnectionController;
use CengizKemec\Cloudflare\Listener\PurgeCacheListener;
use Flarum\Extend;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('forum'))
        ->post('/cloudflare/test', 'cloudflare.test', TestConnectionController::class)
        ->post('/cloudflare/purge', 'cloudflare.purge', PurgeCacheController::class),

    (new Extend\Settings())
        ->default('cloudflare.enabled', '0')
        ->default('cloudflare.api_token', '')
        ->default('cloudflare.account_id', '')
        ->default('cloudflare.zone_id', '')
        ->default('cloudflare.zone_name', '')
        ->default('cloudflare.auto_purge', '0')
        ->default('cloudflare.development_mode', '0'),

    (new Extend\Event())
        ->listen(\Flarum\Discussion\Event\Started::class, PurgeCacheListener::class)
        ->listen(\Flarum\Post\Event\Posted::class, PurgeCacheListener::class),
];