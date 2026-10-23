<?php

declare(strict_types=1);

namespace CengizKemec\Cloudflare\Listener;

use CengizKemec\Cloudflare\CloudflareService;
use Flarum\Settings\SettingsRepositoryInterface;

class PurgeCacheListener
{
    public function __construct(
        private CloudflareService $cloudflare,
        private SettingsRepositoryInterface $settings
    ) {
    }

    public function handle(object $event): void
    {
        if (! $this->cloudflare->isEnabled()) {
            return;
        }

        if (! (bool) $this->settings->get('cloudflare.auto_purge', false)) {
            return;
        }

        try {
            $this->cloudflare->purgeCache();
        } catch (\Throwable) {
            // Ignore failures during automatic purge.
        }
    }
}