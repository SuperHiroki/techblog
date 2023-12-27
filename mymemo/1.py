import feedparser

# RSSフィードのURL（正しいURLに置き換えてください）
feed_url = "https://techblog.zozo.com/rss"

# フィードを解析
feed = feedparser.parse(feed_url)

# フィードの解析エラーをチェック
if feed.bozo:
    print(f"Error parsing feed: {feed.bozo_exception}")
else:
    # エントリが存在するかチェック
    if len(feed.entries) == 0:
        print("No entries found in feed.")
    else:
        # 各記事のタイトルとリンクを表示
        for entry in feed.entries:
            print(f"Title: {entry.title}")
            print(f"Link: {entry.link}")
            published = entry.get("published", "Not available")
            print(f"Published: {published}")
            print("----------------------")





下記の二つの画像を作って。
1. 空のフォルダのアイコン
2. ファイルが入っているフォルダのアイコン

ウェブサイトのボタンに使うので、できる限りシンプルにしてほしい。
白と黒の二色だけを使ってください。
