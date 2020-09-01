<?php

namespace Webike\AssetCache\Tests\Concerns;

use Illuminate\Support\Facades\View;

trait CreatesApplication
{
    protected function getPackageProviders($app)
    {
        return [
            \Webike\AssetCache\AssetCacheServiceProvider::class
        ];
    }
    protected function getPackageAliases($app)
	{
    	return [
        	'LaravelAssetCache' => \Webike\AssetCache\Facades\AssetCache::class,
    	];
	}
}

