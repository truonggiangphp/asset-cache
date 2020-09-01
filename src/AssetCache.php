<?php

namespace Webike\AssetCache;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Carbon\Carbon;

class AssetCache
{
	public $packageName;
	public $version;
	public $filename;
	public $remoteAssetUrl;

    /**
     * AssetCache constructor.
     * @param string $filename
     * @param string $version
     * @param string $remoteAssetUrl
     */
	public function __construct(string $filename, string $version, string $remoteAssetUrl)
	{
		$this->version = $version;
		$this->filename = $this->constructFilenameWithPathAndVersion($filename, $version);
		$this->remoteAssetUrl = $remoteAssetUrl;
	}

    /**
     * @param string $filename
     * @param string $version
     * @return string
     */
	public function constructFilenameWithPathAndVersion(
		string $filename,
		string $version
    ): string
	{
		// Trim the slash is there is one
		$filename = trim($filename, '/');
		// Split at dots
		$parts = explode('.', $filename);
		// Remember the extension
		$extension = array_pop($parts);
		// Add the version and extension back on
		array_push($parts, $version, $extension);
		// Construct the path and filename from the parts
		return implode('.', $parts);
	}

    /**
     * @return string
     */
	public function cachedUrl(): string
	{
		if (! $this->isCached()) {
			//   cache asset
			$this->refreshCachedFile();
		}

		return asset($this->filename);
	}

    /**
     * @return string
     */
	public function cacheKey(): string
	{
		return 'LAC-' . $this->filename . '-cached';
	}

    /**
     * @return bool
     */
	public function isCached(): bool
	{
		return Cache::has($this->cacheKey());
	}

    /**
     * @return void
     */
	public function refreshCachedFile(): void
	{
		try {
			$response = (new Client())->get($this->remoteAssetUrl);
		} catch(\Exception $e) {
			return;
		}

		if (200 !== $response->getStatusCode()) {
			return;
		}

		$contents = $response->getBody();

		Storage::disk('public')->put($this->filename, $contents);

		// Update cache name and timestamp
		Cache::put($this->cacheKey(), time(), Carbon::now()->addHours(1)->timestamp);
	}
}
