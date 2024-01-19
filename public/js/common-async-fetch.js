// public/js/common-async-fetch.js

//定数
const baseUrl = "https://techblog.shiroatohiro.com"

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
    return fetch(url, {
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
