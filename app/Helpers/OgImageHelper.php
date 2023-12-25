<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;


class OgImageHelper
{
    public static function getMetaData($url)
    {
        $context = stream_context_create(
            array("http" => array("header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36"))
        );
        $html = file_get_contents($url, false, $context);

        if ($html === false) {
            Log::info('Error fetching HTML from URL');
            return null;
        }

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);

        $metaData = [];
        $metaData['name'] = self::getContent($xpath, '//meta[@property="og:title"]') ?: self::getContent($xpath, '//title');
        $metaData['thumbnail_url'] = self::getContent($xpath, '//meta[@property="og:image"]/@content');
        $metaData['favicon_url'] = self::getContent($xpath, '//link[@rel="icon"]/@href') ?: self::getContent($xpath, '//link[@rel="shortcut icon"]/@href');
        $metaData['rss_link'] = self::getContent($xpath, '//link[@type="application/rss+xml"]/@href');

        return $metaData;
    }

    private static function getContent($xpath, $query)
    {
        $tags = $xpath->query($query);
        if ($tags->length > 0) {
            return $tags->item(0)->nodeValue;
        }
        return null;
    }
}
