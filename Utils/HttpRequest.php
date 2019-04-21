<?php

declare(strict_types=1);

namespace Utils;


class HttpRequest
{
    private $api;
    private $query;
    private $post;


    public function __construct(string $api, array $query_data, bool $post = true)
    {
        $this->api = $api;
        $this->query = http_build_query($query_data);
        $this->post = $post;
    }

    public function response(): string
    {

        if (!$this->post) {
            return file_get_contents($this->api . '?' . $this->query);
        }

        $context = stream_context_create(
            [
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $this->query,
                    'timeout' => 60
                ]
            ]);
        return file_get_contents($this->api, false, $context);

    }

    public function responseJsonDecoded() : object
    {
        return json_decode($this->response());
    }


}