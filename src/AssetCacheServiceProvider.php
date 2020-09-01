<?php

namespace Webike\AssetCache;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AssetCacheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('laravel-asset-cache', function () {
            return new LaravelAssetCache();
        });
    }

    public function boot()
    {
        // For testing see the example at https://github.com/appstract/laravel-blade-directives/blob/master/tests/DataAttributesTest.php
        Blade::directive('jscript', function ($expression) {
            return '<?php echo "<script src=\\"" . \Webike\AssetCache\Facades\AssetCache::cachedAssetUrl(' . $expression . ') . "\\"></script>\\n" ?>';
        });
    }
}