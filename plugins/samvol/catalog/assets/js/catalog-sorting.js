(function (root) {
    "use strict";

    const Snowboard = root.Snowboard;

    const handleSelect = (select) => {
        const block = select.closest('[data-control="catalog-sorting"]');
        if (!block) {
            return;
        }

        const sortParam = block.dataset.sortParam || "sort";

        try {
            const url = new URL(window.location.href);
            url.searchParams.set(sortParam, select.value);
            window.location.href = url.toString();
        } catch (e) {
            // Fallback: append manually
            const glue = window.location.href.indexOf("?") === -1 ? "?" : "&";
            window.location.href =
                window.location.href +
                glue +
                encodeURIComponent(sortParam) +
                "=" +
                encodeURIComponent(select.value);
        }
    };

    const bindAllSelects = () => {
        document
            .querySelectorAll('[data-control="catalog-sorting"] select')
            .forEach((select) => {
                if (select._catalogSortingBound) {
                    return;
                }
                select.addEventListener(
                    "change",
                    () => handleSelect(select),
                    false
                );
                select._catalogSortingBound = true;
            });
    };

    // Bind delegated handler once DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", bindAllSelects);
    } else {
        bindAllSelects();
    }

    // Ensure handler survives Snowboard AJAX updates (delegation covers it, but keep event as hook if needed)
    if (Snowboard && typeof Snowboard.on === "function") {
        Snowboard.on("ajaxDone", bindAllSelects);
    }
})(window);
