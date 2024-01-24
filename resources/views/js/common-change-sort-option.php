<script>
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//ソートオプションの表示の初期化
function commonInitializeSortOptions(candidates) {
    const urlParams = new URLSearchParams(window.location.search);

    //URLによって選択肢の数値を変更する。
    changeStateFromUrl(urlParams);

    //期間の選択肢を表示するか
    commonChangeDisplayOfPeriodOptions(candidates, urlParams.get('sort'));
}

//URLによって選択肢の数値を変更する。
function changeStateFromUrl(urlParams){
    const sort = urlParams.get('sort');
    const period = urlParams.get('period');
    const keywords = urlParams.get('keywords');

    document.getElementById("sortOption").value = sort;
    if(period){
        document.getElementById("trendingOption").value = period;
    }
    if(keywords){
        document.getElementById("keywords").value = keywords;
    }
}

//期間の選択肢の表示非表示を変更する。
function commonChangeDisplayOfPeriodOptions(candidates, sort){
    if(candidates['normal'].includes(sort)) {
        document.getElementById("trendingOption").style.display = "none";
    }else if(candidates['trending'].includes(sort)){
        document.getElementById("trendingOption").style.display = "block";
    }
}

/////////////////////////////////////////////////////
//ソートオプションの選択が変わった時
function commonUpdateSort(candidates) {
    const parameters = getParametersFromDropdown();
    jumpToUrl(parameters, candidates);
}

//ドロップダウンから取得する。
function getParametersFromDropdown(){
    let parameters = {};
    parameters["sort"] = document.getElementById("sortOption").value;
    parameters["period"] = document.getElementById("trendingOption").value;
    parameters["keywords"] = document.getElementById("keywords").value
    return parameters;
}

//URLを移動する。
function jumpToUrl(parameters, candidates){
    if(candidates['normal'].includes(parameters["sort"])){
        if(parameters["keywords"]){
            window.location.href = window.location.pathname + "?sort=" + parameters["sort"] + "&keywords=" + parameters["keywords"];
        }else{
            window.location.href = window.location.pathname + "?sort=" + parameters["sort"];
        }
    }else if(candidates['trending'].includes(parameters["sort"])){
        if(parameters["keywords"]){
            window.location.href = window.location.pathname + "?sort=" + parameters["sort"] + "&period=" + parameters["period"] + "&keywords=" + parameters["keywords"];
        }else{
            window.location.href = window.location.pathname + "?sort=" + parameters["sort"] + "&period=" + parameters["period"];
        }
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
</script>