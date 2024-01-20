<script>
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//ページネーションのメイン処理
function pagination(lastPage, targetSectionId){
    let currentPage = 1;

    window.addEventListener('scroll', () => {
        if (window.scrollY + window.innerHeight + 1 >= document.documentElement.scrollHeight) {
            loadMoreArticles();
        }
    });

    function loadMoreArticles() {
        if (currentPage >= lastPage) {
            return; // 全てのページが読み込まれた場合は何もしない
        }

        currentPage++;
        const url = new URL(window.location.href);
        url.searchParams.set('page', currentPage);

        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const htmlDocument = parser.parseFromString(data, "text/html");
                const newArticles = htmlDocument.getElementById(targetSectionId).innerHTML;
                document.getElementById(targetSectionId).innerHTML += newArticles;
            });
    }
}
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
</script>