<?php

namespace App\Apis;


use App\Exception\ApiException;
use App\Helper\ConfigHelper;
use Symfony\Component\HttpClient\HttpClient;

class DirectLinkExtrator
{
    public static function getLinkAction(string $url)
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            ConfigHelper::get('direct_link_extractor_url') . '?url=' . urlencode($url),
            [
                'auth_basic' => [ConfigHelper::get('auth_basic_user'), ConfigHelper::get('auth_basic_pass')]
            ]
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new ApiException('failed to extract direct link(1) ' . $statusCode, 500);
        }

        $data = json_decode($response->getContent());
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('failed to extract direct link(2)', 500);
        }

        foreach ($data->links as $l) {
            if (strpos($l->format, 'audio')) {
                return $l->url;
            }
        }

        throw new ApiException('failed to extract direct link(3)', 500);
    }
}
