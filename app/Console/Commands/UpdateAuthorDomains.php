<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Author;

//link_commonのカラムをAuthorsテーブルに追加したとき。
class UpdateAuthorDomains extends Command
{
    // コマンドの名前とシグネチャを設定します。
    protected $signature = 'app:update-author-domains';

    // コマンドの説明を設定します。
    protected $description = 'Update authors table and set the domain of each author';

    public function handle()
    {
        // トランザクションを開始します。
        DB::transaction(function () {
            // Authorモデルの全レコードを取得します。
            $authors = Author::all();
            
            foreach ($authors as $author) {
                // URLからドメイン名を取得します。$articleDomain = parse_url($link, PHP_URL_HOST);
                $parsedUrl = parse_url($author->link);
                $domain = $parsedUrl['host'] ?? null;
                
                // link_commonが既に設定されていないか、または異なる場合のみ更新します。
                if ($author->link_common !== $domain) {
                    $author->link_common = $domain;
                    $author->save();
                }
            }
        });
        
        // 処理が完了したことをコンソールに出力します。
        $this->info('All author domains have been updated.');
    }
}
