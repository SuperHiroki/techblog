<script>
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
//定数
const baseUrl = "https://techblog.shiroatohiro.com"

//ローカルストレージからapiTokenを取得する。
function getApiToken() {
    var isGuest = @guest true @else false @endguest;
    if (isGuest) {
        throw new Error("ログインしていません！！")
    }
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
    return fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': `Bearer ${apiToken}`,
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        const responseMag = response.status + ' ' + response.statusText;
        const contentType = response.headers.get('Content-Type');
        if (contentType && contentType.includes('application/json')) {
            // JSONレスポンスを解析する
            return response.json().then(json => {
                if (response.ok) {
                    return json;
                } else {
                    throw new Error(json.message || responseMag);
                }
            });
        } else {
            // JSONでない場合は、直接statusTextを使用
            throw new Error(responseMag);
        }
    })
    .catch(error => {
        throw error;
    });
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

//チェックを切り替える。
function toggleChecked(icons, currentType, targetType){
    //現在アイコンは一つしかないので、forループを使う必要はない。
    icons.forEach(function(icon) {
        if(icon.dataset.currentType === targetType){
            icon.style.display = 'block'; 
        }else if (icon.dataset.currentType === currentType){
            icon.style.display = 'none'; 
        }
    });
}

//ゴミ箱に入れたらオーバーレイを適用する。
function toggleTrashOverlay(overlaySection, targetType) {
    if(targetType == 'untrash'){
        overlaySection.classList.remove('gray-overlay');
    }else if(targetType == 'trash'){
        overlaySection.classList.add('gray-overlay');
    }
}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

</script>