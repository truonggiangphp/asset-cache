<?php

namespace Webike\AssetCache;

class LaravelAssetCache
{

    /**
     * Generate the URL of a cached asset (and cache it or update the cached
     * file if necessary along the way)
     *
     * Note: We require a filename here because we need to cache the file with the
     * correct filename.
     * @param string $versionConstraint
     * @param string $filename
     * @return string
     */
    public function cachedAssetUrl(
        string $versionConstraint,
        string $filename
    ): string
    {
        return (new AssetCache(
            $filename,
            $versionConstraint,
            $this->remoteAssetUrl($versionConstraint, $filename)
        ))->cachedUrl();
    }

    /**
     * Generate the URL for an asset given its NPM package name
     * and, optionally, a version constraint and a filename
     * @param string $versionConstraint
     * @param string $filename
     * @return string
     */
    public function remoteAssetUrl(
        string $versionConstraint = '',
        string $filename = ''
    ): string
    {
        return sprintf(
            $this->urlPattern($versionConstraint, $filename),
            $this->normaliseVersionConstraint($versionConstraint),
            $this->normaliseFilename($filename));
    }

    /**
     * Build us a pattern for use in sprinting the URL
     * @param string $versionConstraint
     * @param string $filename
     * @return string
     */
    public function urlPattern(
        string $versionConstraint = '',
        string $filename = ''): string
    {
        $urlPattern = "https://cdn.jsdelivr.net/npm/%s";

        if (!empty($versionConstraint)) {
            $urlPattern .= '@%s';
        } else {
            // We still pass it in so the pattern needs to contain it
            // even though it's empty
            $urlPattern .= '%s';
        }

        if (!empty($filename)) {
            $urlPattern .= '/%s';
        }

        return $urlPattern;
    }

    /**
     * @param string $versionConstraint
     * @return string
     */
    public function normaliseVersionConstraint(string $versionConstraint): string
    {
        return $versionConstraint;
    }

    /**
     * @param string $filename
     * @return string
     */
    public function normaliseFilename(string $filename): string
    {
        return trim($filename, "/");
    }

}