<?php

class RequestHandler
{
    private string $base_url;
    private string $version;
    private Client $client;

    /**
     * Instantiate a new RequestHandler
     */
    public function __construct()
    {
        $this->base_url = 'https://openlibrary.org';
        $this->version = '1.0.0';
        $this->client = new Client([
            'allow_redirects' => false
        ]);
    }
}
