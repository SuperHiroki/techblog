<script>
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//ソートオプションの表示の初期化
function commonInitializeSortOptions(candidates) {
    const urlParams = new URLSearchParams(window.location.search);
    const sort = urlParams.get('sort');
    const period = urlParams.get('period');

    document.getElementById("sortOption").value = sort;

    if(candidates['normal'].includes(sort)) {
        document.getElementById("trendingOption").style.display = "none";
    } else if (candidates['trending'].includes(sort)){
        document.getElementById("trendingOption").style.display = "block";
        document.getElementById("trendingOption").value = period;
    }
}

//ソートオプションの選択が変わった時
function commonUpdateSort(candidates) {
    const sort = document.getElementById("sortOption").value;
    const period = document.getElementById("trendingOption").value;

    if (candidates['normal'].includes(sort)) {
        document.getElementById("trendingOption").style.display = "none";
        location = window.location.pathname + "?sort=" + sort;
    } else if (candidates['trending'].includes(sort)) {
        document.getElementById("trendingOption").style.display = "block";
        location = window.location.pathname + "?sort=" + sort + "&period=" + period;
    } 
}
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
</script>