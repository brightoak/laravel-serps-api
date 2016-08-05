<?php
namespace BrightOak\Serps;

use BrightOak\Serps\Exceptions\SerpsException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

// Several functions in this class inspired by and borrowed from https://github.com/spatie/laravel-analytics

class Serps
{

    protected $client;

    protected $apiKey;

    protected $oganizationId;


    /** @var int */
    protected $cacheLifeTimeInMinutes = 0;

    public function __construct($options = null)
    {

        if ($options) {
            $this->client = new Client($options);
        } else {
            $this->client = new Client(['base_uri' => 'http://api.serps.com/']);
        }
        $this->setCacheLifeTimeInMinutes(env('SERPS_CACHE_LIFETIME'));

        $this->apiKey = env('SERPS_API_KEY');

    }

    /**
     * Set the cache time.
     *
     * @param int $cacheLifeTimeInMinutes
     *
     * @return self
     */
    public function setCacheLifeTimeInMinutes( $cacheLifeTimeInMinutes)
    {
        $this->cacheLifeTimeInMinutes = $cacheLifeTimeInMinutes;

        return $this;
    }

    public function getAllSites()
    {
        $url = 'types/organizations/items/' . env('SERPS_ORGANIZATION_ID') . '/children/sites/items/';
        return $this->performQuery($url);

    }

    public function performQuery( $url)
    {
        $cacheName = $this->determineCacheName($url);

        if ($this->cacheLifeTimeInMinutes == 0) {
            Cache::forget($cacheName);
        }

        return Cache::remember($cacheName, $this->cacheLifeTimeInMinutes, function () use ($url) {
            $request = $this->client->get($url, [
                'headers' => ['X-Api-Token' => env('SERPS_API_KEY')],
                'auth' => [env('SERPS_USER'), env('SERPS_PASS')]
            ]);
            if ($request->getStatusCode() == 200) {
                return json_decode($request->getBody()->getContents());
            }

            throw SerpsException::error($request);

        });
    }

    /*
   * Determine the cache name for the set of query properties given.
   */
    protected function determineCacheName( $properties)
    {
        return 'brightoak.laravel-serps-api.'.md5($properties);
    }



}