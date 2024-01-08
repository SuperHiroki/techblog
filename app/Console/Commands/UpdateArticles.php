<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use App\Models\Article;
use App\Models\Author;

use App\Helpers\OgImageHelper;

use App\Utilities\RssReader;

class UpdateArticles extends Command
{
    protected $signature = 'articles:update';
    protected $description = 'Updates articles from RSS feeds of authors';

    public function handle()
    {
        //開始の合図。
        $this->info('PPPPPPPPPPPPPPPPPPPPPPPP [articles:update] start!');
        Log::info('PPPPPPPPPPPPPPPPPPPPPPPP [articles:update] start!');

        $authors = Author::all();

        foreach ($authors as $author) {
            if ($author->rss_link) {
                try{
                    $articles = RssReader::parse($author->rss_link);
                }catch (\Exception $e) {
                    $this->error("LLLLLLLLLLLLLLLLLLLLLL Error in RssReader::parse: " . $e->getMessage());
                    Log::error("LLLLLLLLLLLLLLLLLLLLLL Error in RssReader::parse: " . $e->getMessage());
                }

                foreach ($articles as $articleData) {
                    try {
                        // 既に存在するかどうかをチェック
                        $existingArticle = Article::where('link', $articleData->link)->first();
                        if ($existingArticle) {
                            $this->info("DDDDDDDDDDDDDDD Article [{$author->name}({$existingArticle->created_date}): {$existingArticle->title}] already exists. Update to article [{$author->name}({$articleData->pubDate}): {$articleData->title}].");
                            Log::info("DDDDDDDDDDDDDDD Article [{$author->name}({$existingArticle->created_date}): {$existingArticle->title}] already exists. Update to article [{$author->name}({$articleData->pubDate}): {$articleData->title}].");
                            $metaData = OgImageHelper::getMetaData($articleData->link);
                            Article::updateArticle($articleData->link, $metaData, $articleData->pubDate);
                        }else{
                            $this->info("QQQQQQQQQQQ Article [{$author->name}({$articleData->pubDate}): {$articleData->title}] created.");
                            Log::info("QQQQQQQQQQQ Article [{$author->name}({$articleData->pubDate}): {$articleData->title}] created.");
                            $metaData = OgImageHelper::getMetaData($articleData->link);
                            Article::createWithDomainCheck($articleData->link, $metaData, $articleData->pubDate);
                        }
                    } catch (\Exception $e) {
                        $this->error("WWWWWWWWWWWWWWWWWWWWWWWWW Error in add article: " . $e->getMessage());
                        Log::error("WWWWWWWWWWWWWWWWWWWWWWWWW Error in add article: " . $e->getMessage());
                    }
                }
            }
        }

        //終了の合図。
        $this->info('CCCCCCCCCCCCCCCCCCC [articles:update] finished!');
        Log::info('CCCCCCCCCCCCCCCCCCC [articles:update] finished!');
    }
}
