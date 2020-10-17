<?php
/**
 * Created by PhpStorm.
 * User: sama
 * Date: 10/16/20
 * Time: 6:27 PM
 */

namespace App\Services;


use GuzzleHttp\Client;

class BaseService
{
    protected $base_url;
    protected $client;

    public function __construct(array $parameters)
    {

        $this->base_url = $parameters['base_url'];
        $this->client = new Client([
            'headers' => $parameters['headers'] ?? null,
            'base_uri' => $parameters['base_url']
        ]);
    }
}