<?php

use Webike\AssetCache\Facades\AssetCache;

if (!function_exists('rebuildAssetsUrl')) {
    /**
     * @param string $path
     * @return mixed
     */
    function rebuildAssetsUrl(string $path)
    {
        $version = config('assets.version');
        return AssetCache::cachedAssetUrl($version, $path);
    }
}