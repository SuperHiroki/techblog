<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Author;
use App\Helpers\OgImageHelper;

//すべての著者をアップデート。descriptionのカラムを追加したときに使った。
class UpdateAuthors extends Command
{
    protected $signature = 'update:authors';
    protected $description = 'Update all authors';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            try {
                $metaData = OgImageHelper::getMetaData($author->link);
                Author::updateAuthor($author->link, $metaData);
                $this->info("Author updated: {$author->name}");
            } catch (\Exception $e) {
                $this->error("Failed to update author: {$author->name}. Error: {$e->getMessage()}");
            }
        }

        return 0;
    }
}
