async function announcement(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    let tab = '';
    let data = await response.json();
    for (let r of data.getNews) {
        tab += `<li>${r.news_desc}</li>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById('announcement').innerHTML = tab;
}

async function editorChoice(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    let data = await response.json();
    if (data.getEditorChoice != '') {
        let tab = `<h5 class="py-1 blue-colour fw-bold mt-lg-0 mt-3">Editor Choice</h5>
                        <div class="card card-body">
                            <div class="swiper mySwiper" style="height: 270px">
                                <div class="swiper-wrapper">`;
        for (let r of data.getEditorChoice) {
            let image = r.fileimage
                ? 'https://www.johs.com.sa/admin/public/uploadss/187/' + r.fileimage
                : 'assets/images/capture.png';
            tab += `<div class="swiper-slide">
                            <a href="abstract.php?id=${r.article_id}" class="text-decoration-none text-dark" >
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12 text-center">
                                        <img
                                            src="${image}"
                                            class="img-fluid"
                                        />
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="fw-bold">${r.title}</div>
                                        <div class="text-justify mt-2" >${r.authors}</div>
                                    </div>
                                </div>
                            </a>
                        </div>`;
        }
        tab += `</div>
                        <div class="swiper-button-next text-dark h2"></div>
                        <div class="swiper-button-prev text-dark h2"></div>
                    </div>
                </div>`;
        // Setting innerHTML as tab variable
        document.getElementById('editorChoice').innerHTML = tab;
    }
}

async function currentIssue(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    let data = await response.json();
    if (data.currentIssueArticle != '') {
        let tab = `<div class="conatainer-fluid">
                        <div class="card border-0 bglight">
                            <div class="border-0 d-flex justify-content-between px-5 mt-4">
                                <h5 class="blue-colour fw-bold">Current Issue</h5>
                                <a href="" class="card-link">Show All</a>
                            </div>
                            <div class="card-body py-0">
                                <div class="swiper mySwipers">
                                    <div class="swiper-wrapper">`;
        for (let r of data.currentIssueArticle) {
            let image = r.fileimage
                ? 'https://www.johs.com.sa/admin/public/uploadss/187/' + r.fileimage
                : 'assets/images/capture.png';
            tab += `<div class="swiper-slide" style="background: none;">
                        <a href="abstract.php?id=${r.article_id}" class="text-decoration-none text-dark">
                            <div class="card mx-3 mb-2">
                                <img src="${image}" class="card-img-top p-4" alt="Saudi Medical Journal" />
                                <div class="card-body">
                                    <div class="fw-bold">${r.title}</div>
                                    <div class="text-justify mt-2" >${r.authors}</div>
                                </div>
                            </div>
                        </a>
                    </div>`;
        }
        tab += `</div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        // Setting innerHTML as tab variable
        document.getElementById('currentIssue').innerHTML = tab;
    }
}

async function trendingArticles(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    let data = await response.json();
    if (data.getTrending_articles != '') {
        let tab = `<div class="conatainer-fluid">
                        <div class="card border-0">
                            <div class="border-0 d-flex justify-content-between px-5 mt-4">
                                <h5 class="blue-colour fw-bold">Trending</h5>
                            </div>
                            <div class="card-body py-0">
                                <div class="swiper mySwipers">
                                    <div class="swiper-wrapper">`;
        for (let r of data.getTrending_articles) {
            let image = r.fileimage
                ? 'https://www.johs.com.sa/admin/public/uploadss/187/' + r.fileimage
                : 'assets/images/capture.png';
            tab += `<div class="swiper-slide" style="background: none;">
                        <a href="abstract.php?id=${r.article_id}" class="text-decoration-none text-dark">
                            <div class="card mx-3 mb-2">
                                <img src="${image}" class="card-img-top p-4" alt="Saudi Medical Journal" />
                                <div class="card-body">
                                    <div class="fw-bold">${r.title}</div>
                                    <div class="text-justify mt-2" >${r.authors}</div>
                                </div>
                            </div>
                        </a>
                    </div>`;
        }
        tab += `</div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        // Setting innerHTML as tab variable
        document.getElementById('trendingArticles').innerHTML = tab;
    }
}

async function mostView(url) {
    // Storing response
    const response = await fetch(url);
    // Storing data in form of JSON
    let data = await response.json();
    if (data.getMostViewArticleApi != '') {
        let tab = `<div class="conatainer-fluid">
                        <div class="card border-0 bglight">
                            <div class="border-0 d-flex justify-content-between px-5 mt-4">
                                <h5 class="blue-colour fw-bold">Most Viewed</h5>
                            </div>
                            <div class="card-body py-0">
                                <div class="swiper mySwipers">
                                    <div class="swiper-wrapper">`;
        for (let r of data.getMostViewArticleApi) {
            let image = r.fileimage
                ? 'https://www.johs.com.sa/admin/public/uploadss/187/' + r.fileimage
                : 'assets/images/capture.png';
            tab += `<div class="swiper-slide" style="background: none;">
                        <a href="abstract.php?id=${r.article_id}" class="text-decoration-none text-dark">
                            <div class="card mx-3 mb-2">
                                <img src="${image}" class="card-img-top p-4" alt="Saudi Medical Journal" />
                                <div class="card-body">
                                    <div class="fw-bold">${r.title}</div>
                                    <div class="text-justify mt-2" >${r.authors}</div>
                                </div>
                            </div>
                        </a>
                    </div>`;
        }
        tab += `</div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
            </div>`;
        // Setting innerHTML as tab variable
        document.getElementById('mostView').innerHTML = tab;
    }
}

async function advertisement(url) {
    const response = await fetch(url);
    let data = await response.json();
    if (data.adbannerid != '') {
        let tab = `<div class="swiper mySwiper">
                    <div class="swiper-wrapper">`;
        for (let r of data.adbannerid) {
            tab += `<div class="swiper-slide">
                        <div>${r.name}</div>
                    </div>`;
        }
        tab += `</div>
                    <div class="swiper-button-next text-white h2"></div>
                    <div class="swiper-button-prev text-white h2"></div>
                </div>
            </div>`;
        // Setting innerHTML as tab variable
        document.getElementById('advertisement').innerHTML = tab;
    }
}

async function journalStatistics(url) {
    const response = await fetch(url);
    let data = await response.json();
    document.getElementById('getSubmittedArticlesCount').innerHTML =
        data.getArticlesCount[0].total_countSA;
    document.getElementById('getPublishedArticlesCount').innerHTML =
        data.getArticlesCount[1].total_countPA;
    document.getElementById('getTotalDownloads').innerHTML =
        data.getArticlesCount[2].total_countTD;
}

async function year() {
    const date = new Date();
	document.getElementById('date').innerHTML = date.getFullYear();
}
