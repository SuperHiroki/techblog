//js/common-in-all-js.blade.php

<script>
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
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
////////////////////////////////////////////////////////////////////////////////////////
// 監視することでフラッシュメッセージの表示と非表示を切り替える。
const targetNodes = document.getElementsByClassName('flush_msg');
const config = { characterData: true, childList: true };
const callback = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if(mutation.type === 'characterData' || mutation.type === 'childList') {
            const target = mutation.target;
            if(target.textContent.length === 0) {
                target.style.display = 'none';
            } else {
                target.style.display = 'block';
            }
        }
    }
};
const observer = new MutationObserver(callback);
Array.from(targetNodes).forEach(node => {
    observer.observe(node, config);
});


//動的に、コンテンツが固定されたヘッダーに隠れないようにする。
const config_fixed_header = { attributes: true, characterData: true, childList: true, subtree: true };
const callback_fixed_header = function(mutationsList, observer) {
    for(let mutation of mutationsList) {
        if(mutation.type === 'attributes' || mutation.type === 'characterData'  || mutation.type === 'childList' || mutation.type === 'subtree') {
            const target = mutation.target;
            adjustPaddingTop();
        }
    }
};
const observer_fixed_header = new MutationObserver(callback_fixed_header);
observer_fixed_header.observe(fixedHeader, config_fixed_header);

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//idを取得する。
var flush_success = document.getElementById('flush_success');
var flush_error = document.getElementById('flush_error');

//フラッシュメッセージでエラーと成功が同時に表示されないようにする。
function showFlush(error_or_success="success", msg){
    if(error_or_success=="success"){
        flush_success.style.display = "block";
        flush_success.innerText = msg
        flush_error.style.display = "none";
    }else if(error_or_success=="error"){
        flush_success.style.display = "none";
        flush_error.style.display = "block";
        flush_error.innerText = msg;
    }
}


</script>