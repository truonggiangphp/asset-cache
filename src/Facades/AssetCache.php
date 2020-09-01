<?php

namespace Webike\AssetCache\Facades;

use Illuminate\Support\Facades\Facade;

class AssetCache extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'laravel-asset-cache';
	}
}
