// public/js/common-async-fetch.js

//定数
const baseUrl = "https://techblog.shiroatohiro.com"

//非同期でいいね（ブックマーク、アーカイブ）をつけるために設定
document.querySelectorAll('.icon-to-add-func').forEach(item => {
    item.addEventListener('click', async function () {
        try {
            //apiトークンの取得
            const apiToken = getApiToken();
            //記事ID
            const articleId = this.dataset.articleId;
            //いいね（ブックマーク、アーカイブ）などのリクエストの種類
            const currentType = this.dataset.currentType;
            const resultType = revserseType(currentType);
            //メソッド
            const method = getMethod(resultType);
            //URL
            const url = `${baseUrl}/api/${resultType}-article/${articleId}`;
            //fetch
            const jsonData = await fetchApi(url, method, apiToken); 
            //UIの切り替え。
            toggleChecked(articleId, currentType, resultType);
            trashOverlay(articleId, currentType, resultType);
            //フラッシュメッセージ
            document.getElementById('flush_success').innerText = jsonData.message;
        } catch (error) {
            document.getElementById('flush_error').innerText = error;
            console.error('Error:', error);
        }
    });
});

//ローカルストレージからapiTokenを取得する。
function getApiToken() {
    const apiToken = localStorage.getItem('apiToken');
    if (!apiToken) {
        throw new Error("apiToken not found!!")
    }
    return apiToken;
}

//メソッドを取得する。
function getMethod(resultType) {
    let method;
    if (resultType.startsWith('un')) {
        method = 'DELETE';
    } else {
        method = 'POST';
    }
    return method;
}

//非同期でリクエストを投げる。
function fetchApi(url, method, apiToken) {

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': `Bearer ${apiToken}`,
            'Content-Type': 'application/json',
        },
    })
        .then(response => {
            const contentType = response.headers.get('Content-Type');
            if (contentType && contentType.includes('application/json')) {
                // JSONレスポンスを解析する
                return response.json().then(json => {
                    if (response.ok) {
                        return json;
                    } else {
                        throw new Error(json.message || response.statusText);
                    }
                });
            } else {
                // JSONでない場合は、直接statusTextを使用
                throw new Error(response.statusText);
            }
        })
        .catch(error => {
            throw error;
        });
}

//いいね（ブックマーク、アーカイブ）の表示を変更する。
function toggleChecked(articleId, currentType, resultType) {
    const icons = document.querySelectorAll('.icon-to-add-func[data-article-id="' + articleId + '"]');

    icons.forEach(function (icon) {
        if (icon.dataset.currentType === resultType) {
            icon.style.display = 'block';
        } else if (icon.dataset.currentType === currentType) {
            icon.style.display = 'none';
        }
    });
}

//ゴミ箱に入れたらオーバーレイを適用する。
function trashOverlay(articleId, currentType, resultType) {
    const overlaySection = document.getElementById(`for-gray-overlay-${articleId}`);

    if (currentType == 'trash') {
        overlaySection.classList.remove('gray-overlay');
    } else if (currentType == 'untrash') {
        overlaySection.classList.add('gray-overlay');
    }
}

//タイプを逆転する。
function revserseType(type) {
    let reversedType;
    if (type.startsWith('un')) {
        reversedType = type.substring(2);
    } else {
        reversedType = 'un' + type;
    }
    return reversedType
}
