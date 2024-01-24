<script>
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////ヘッダーが二つか一つかによってコンテントのトップの位置を調整する。
//ヘッダーとコンテンツ
var fixedHeader = document.getElementById('fixed-header');
var containerContent = document.getElementById('containerContent');

//ページ読み込み時に、コンテンツが固定されたヘッダーに隠れないようにする。
document.addEventListener("DOMContentLoaded", function() {
    adjustPaddingTop();
});

//コンテントのpaddingTopを再調整する関数
function adjustPaddingTop(){
    var headerHeight = fixedHeader.offsetHeight;
    containerContent.style.paddingTop = headerHeight + 'px';
}

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////フラッシュメッセージ
//デフォルトのフラッシュメッセージはページ読み込みしてから数秒後に消す。
document.addEventListener("DOMContentLoaded", function() {
    var flushElements = document.querySelectorAll('.flush_msg_default'); 
    hideDefaultFlushAfterSomeSeconds(flushElements);
});

//数秒後にその要素を消す。
async function hideDefaultFlushAfterSomeSeconds(elements) {
    if(!(elements instanceof NodeList || Array.isArray(elements))){
        elements = [elements];
    }

    await new Promise(resolve => setTimeout(resolve, 3000));

    elements.forEach(element => {
        element.style.display = "none";
    });
}

////////////////////////////////////////////////////////////
//カスタムのフラッシュメッセージ
var flush_success = document.getElementById('flush_success');
var flush_error = document.getElementById('flush_error');
var timeoutId;

//フラッシュメッセージでエラーと成功が同時に表示されないようにする。
async function showFlush(error_or_success="success", msg){
    if(error_or_success=="success"){
        flush_success.innerText = msg
        await hideFlushAfterSomeSeconds(error_or_success);
    }else if(error_or_success=="error"){
        flush_error.innerText = msg
        await hideFlushAfterSomeSeconds(error_or_success);
    }
}

//過去のタイマーをリセットする。
async function hideFlushAfterSomeSeconds(error_or_success){
    clearTimeout(timeoutId);

    if(error_or_success=="success"){
        flush_success.style.display = "block";
        flush_error.style.display = "none"
    }else if(error_or_success=="error"){
        flush_success.style.display = "none";
        flush_error.style.display = "block"
    }

    timeoutId = setTimeout(() => {
        flush_success.style.display = "none";
        flush_error.style.display = "none"
    }, 3000);
}
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
</script>