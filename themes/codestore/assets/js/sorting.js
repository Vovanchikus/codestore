document.addEventListener("DOMContentLoaded", function () {
    var list = document.querySelector(".catalog__sort-menu");
    var containerCurrent =
        list && list.dataset ? list.dataset.currentSort || null : null;
    var currentSort = getCurrentSort() || containerCurrent;
    if (!list) return;

    // Мы предполагаем, что HTML уже сгенерирован в шаблоне.
    // JS только навешивает обработчик кликов на существующие кнопки.
    attachListHandler(list);
    // Также подсветим ссылки в простом списке сортировки и обеспечим мгновенную подсветку при клике
    applyActiveToLinks();

    function navigateWithSort(sortValue) {
        try {
            var url = new URL(window.location.href);
            url.searchParams.set("sort", sortValue);
            url.searchParams.delete("page");
            window.location.href = url.toString();
        } catch (err) {
            var href =
                window.location.pathname +
                "?sort=" +
                encodeURIComponent(sortValue);
            window.location.href = href;
        }
    }

    function getCurrentSort() {
        try {
            return new URL(window.location.href).searchParams.get("sort");
        } catch (e) {
            var m = window.location.search.match(/[?&]sort=([^&]+)/);
            return m ? decodeURIComponent(m[1]) : null;
        }
    }

    // Рендеринг DOM теперь выполняется сервером в шаблоне; функция удалена.

    function attachListHandler(listEl) {
        var links = Array.from(listEl.querySelectorAll(".catalog__sort-link"));
        links.forEach(function (a) {
            a.addEventListener(
                "click",
                function (ev) {
                    ev.preventDefault();
                    var asc = a.getAttribute("data-asc") || null;
                    var desc = a.getAttribute("data-desc") || null;
                    var explicit = a.getAttribute("data-sort") || null;

                    var effectiveCur =
                        getCurrentSort() ||
                        (listEl && listEl.dataset
                            ? listEl.dataset.currentSort
                            : null);

                    var target = null;
                    if (explicit) {
                        target = explicit;
                    } else if (asc || desc) {
                        if (
                            effectiveCur &&
                            desc &&
                            effectiveCur === desc &&
                            asc
                        ) {
                            target = asc;
                        } else if (
                            effectiveCur &&
                            asc &&
                            effectiveCur === asc &&
                            desc
                        ) {
                            target = desc;
                        } else {
                            target = desc || asc;
                        }
                    }

                    var all = Array.from(
                        document.querySelectorAll(".catalog__sort-link")
                    );
                    all.forEach(function (x) {
                        x.classList.remove("active", "asc", "desc");
                    });
                    a.classList.add("active");
                    if (target && /(_asc$)/.test(target))
                        a.classList.add("asc");
                    else if (target && /(_desc$)/.test(target))
                        a.classList.add("desc");

                    setTimeout(function () {
                        if (target) navigateWithSort(target);
                    }, 120);
                },
                { passive: false }
            );
        });
    }

    function applyActiveToLinks() {
        var cur = getCurrentSort();
        var links = Array.from(
            document.querySelectorAll(".catalog__sort-link, .sort__link")
        );
        if (!links.length) return;

        links.forEach(function (a) {
            // determine sort value from link href
            var linkSort = null;
            try {
                var u = new URL(a.href, window.location.href);
                linkSort = u.searchParams.get("sort");
            } catch (e) {
                // ignore
            }

            if (cur && linkSort && linkSort === cur) {
                a.classList.add("active");
                if (/_asc$/.test(cur)) {
                    a.classList.remove("desc");
                    a.classList.add("asc");
                } else if (/_desc$/.test(cur)) {
                    a.classList.remove("asc");
                    a.classList.add("desc");
                }
            } else {
                a.classList.remove("active");
            }

            // ensure clicking highlights immediately (page will navigate afterwards)
            a.addEventListener(
                "click",
                function (ev) {
                    ev.preventDefault();
                    var self = this;
                    links.forEach(function (x) {
                        x.classList.remove("active", "asc", "desc");
                    });
                    self.classList.add("active");
                    var ts = linkSort || null;
                    if (ts && /_asc$/.test(ts)) self.classList.add("asc");
                    else if (ts && /_desc$/.test(ts))
                        self.classList.add("desc");

                    // rotate arrow then navigate
                    rotateArrowForElement(self, ts, 200, function () {
                        if (ts) navigateWithSort(ts);
                        else navigateWithSort("");
                    });
                },
                { passive: false }
            );
        });
    }
});
