<?php
namespace BrightOak\Serps;

use BrightOak\Serps\Exceptions\SerpsException;
use GuzzleHttp\Client;


class Serps
{

    protected $client;
    protected $apiKey;
    protected $oganizationId;

    public function __construct($options = null)
    {

        if ($options) {
            $this->client = new Client($options);
        } else {
            $this->client = new Client(['base_uri' => 'http://api.serps.com/']);
        }
        $this->apiKey = env('SERPS_API_KEY');
    }

    public function getAllSites()
    {
        $url = 'types/organizations/items/' . env('SERPS_ORGANIZATION_ID') . '/children/sites/items/';
        $request = $this->client->get($url, [
            'headers' => ['X-Api-Token' => env('SERPS_API_KEY')],
            'auth' => [env('SERPS_USER'), env('SERPS_PASS')]
        ]);
        if ($request->getStatusCode() == 200) {
            return json_decode($request->getBody()->getContents());
        }

        throw SerpsException::error($request);

    }


}