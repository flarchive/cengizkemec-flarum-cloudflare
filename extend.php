<?php

use CengizKemec\Cloudflare\Api\Controller\ApplySettingsController;
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
        ->post('/cloudflare/purge', 'cloudflare.purge', PurgeCacheController::class)
        ->post('/cloudflare/apply', 'cloudflare.apply', ApplySettingsController::class),

    (new Extend\Settings())
        ->default('cloudflare.enabled', '0')
        ->default('cloudflare.api_token', '')
        ->default('cloudflare.account_id', '')
        ->default('cloudflare.zone_id', '')
        ->default('cloudflare.zone_name', '')
        ->default('cloudflare.auto_purge', '0')
        ->default('cloudflare.development_mode', '0')
        ->default('cloudflare.bot_fight_mode', '0')
        ->default('cloudflare.always_online', '0')
        ->default('cloudflare.brotli', '0')
        ->default('cloudflare.rocket_loader', '0')
        ->default('cloudflare.mirage', '0')
        ->default('cloudflare.minify_js', '0')
        ->default('cloudflare.minify_css', '0')
        ->default('cloudflare.minify_html', '0')
        ->default('cloudflare.security_level', 'medium')
        ->default('cloudflare.polish', 'off')
        ->default('cloudflare.browser_cache_ttl', '14400')
        ->default('cloudflare.ssl_mode', 'flexible')
        ->default('cloudflare.always_use_https', '0')
        ->default('cloudflare.min_tls_version', '1.2')
        ->default('cloudflare.tls_1_3', 'on')
        ->default('cloudflare.automatic_https_rewrites', '1')
        ->default('cloudflare.http2', '1')
        ->default('cloudflare.http3', '1')
        ->default('cloudflare.0rtt', '0')
        ->default('cloudflare.early_hints', '1')
        ->default('cloudflare.opportunistic_encryption', '1'),

    (new Extend\Event())
        ->listen(\Flarum\Discussion\Event\Started::class, PurgeCacheListener::class)
        ->listen(\Flarum\Post\Event\Posted::class, PurgeCacheListener::class),
];