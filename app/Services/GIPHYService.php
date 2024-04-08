<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GIPHYService
{
    /** @var url $url */
    protected $url;

    /** @var string $api_key */
    protected $api_key;

    /** @var $instance */
    private static $instance;

    public function __construct()
    {
        $this->url = env('GIPHY_BASE_URL');
        $this->api_key = env('GIPHY_API_KEY');
    }

    private static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get gifs list by query
     *
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return mixed
     **/
    public static function searchGif (string $query, int $limit = null, int $offset = null) {
        $service = self::getInstance();

        $params = [
            'q' => $query,
            'api_key' => $service->api_key
        ];

        if (!is_null($limit)) $params['limit'] = $limit;
        if (!is_null($offset)) $params['offset'] = $offset;

        $response = Http::get($service->url . '/search', $params);
        $body = json_decode($response->body());

        if ($response->failed()) {
            return ['status' => 'error', 'message' => $body->meta->msg];
        }

        return $body;
    }

    /**
     * Search GIF by ID
     *
     * @param string $id
     * @return mixed
     **/
    public static function searchById(string $id)
    {
        $service = self::getInstance();

        $params = [
            'api_key' => $service->api_key
        ];

        $response = Http::get($service->url . '/' . $id, $params);
        $body = json_decode($response->body());

        if ($response->failed()) {
            return ['status' => 'error', 'message' => $body->meta->msg];
        }

        return $body;
    }
}
