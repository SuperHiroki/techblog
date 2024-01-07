<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use SimpleXMLElement;

class RssReader
{
    public static function parse($rssUrl)
    {
        Log::info('YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY parse start.');
        
        $response = Http::get($rssUrl);
        if ($response->successful()) {
            return self::extractArticlesFromFeed($response->body());
        } else {
            throw new \Exception('Failed to fetch RSS feed.');
        }
    }

    protected static function extractArticlesFromFeed($feedContent)
    {
        $articles = [];
        $xml = new SimpleXMLElement($feedContent);

        foreach ($xml->channel->item as $item) {

            $pubDate = new \DateTime((string) $item->pubDate);

            //Log::info("pubDate: " . $pubDate);

            $formattedPubDate = $pubDate->format('Y-m-d H:i:s');

            $articles[] = (object) [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'pubDate' => $formattedPubDate,
            ];
        }

        return $articles;
    }
}
