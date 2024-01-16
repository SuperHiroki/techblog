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
            throw new \Exception('Unable to fetch metadata from the link.');
        }
        
        //エンコードの形式がutf-8以外の時も対応できるように。
        $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $html;

        $doc = new DOMDocument();
        @$doc->loadHTML($html);
        $xpath = new DOMXPath($doc);


        $metaData = [];
        $metaData['title'] = self::getContent($xpath, '//meta[@property="og:title"]/@content') ?: self::getContent($xpath, '//title') ?: self::getContent($xpath, '//meta[@property="twitter:title"]/@content') ?: self::getContent($xpath, '//meta[@property="og:site_name"]/@content');
        $metaData['thumbnail_url'] = self::getContent($xpath, '//meta[@property="og:image"]/@content') ?: self::getContent($xpath, '//meta[@property="twitter:image"]/@content') ?: self::getContent($xpath, '//meta[@property="og:image:secure_url"]/@content') ?: self::getContent($xpath, '//meta[@itemprop="image"]/@content');
        $metaData['favicon_url'] = self::getContent($xpath, '//link[@rel="icon"]/@href') ?: self::getContent($xpath, '//link[@rel="shortcut icon"]/@href') ?: self::getContent($xpath, '//link[@rel="apple-touch-icon"]/@href');
        $metaData['rss_link'] = self::getContent($xpath, '//link[@type="application/rss+xml"]/@href') ?: self::getContent($xpath, '//link[@type="application/atom+xml"]/@href');
        $metaData['description'] = self::getContent($xpath, '//meta[@property="og:description"]/@content') ?: self::getContent($xpath, '//meta[@name="description"]/@content') ?: self::getContent($xpath, '//meta[@name="twitter:description"]/@content');

        if (!str_starts_with($metaData['rss_link'], 'https://' . parse_url($url, PHP_URL_HOST))) {
            $metaData['rss_link'] = 'https://' . parse_url($url, PHP_URL_HOST) . $metaData['rss_link'];
        }

        Log::info('FFFFFF ' . $metaData['rss_link']);
        
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
