(function ($) {
    function detectIdFromUrl() {
        var path = window.location.pathname || "";
        // Matches .../catalogs/update/123 or .../catalogs/preview/123
        var match = path.match(/catalogs\/(update|preview)\/(\d+)/);
        var idFromUrl = match ? match[2] : "";
        return idFromUrl;
    }

    function detectIdFromForm() {
        // Winter forms place a hidden "id" field; fall back to Catalog[id]
        var $id = $('input[name="id"], input[name="Catalog[id]"]').filter(
            ":first"
        );
        var idVal = $id.length ? $id.val() : "";
        return idVal;
    }

    function getCatalogId() {
        var id = detectIdFromForm();
        if (id) {
            return id;
        }
        id = detectIdFromUrl();
        return id || "";
    }

    function isNewCatalog() {
        var id = getCatalogId();
        var isNew = !id;
        return isNew;
    }

    function disableTabs() {
        var $tabs = $(".nav-tabs li:not(:first-child) a");
        if ($tabs.data("tabsGuardBound")) return;

        $tabs
            .data("tabsGuardBound", true)
            .addClass("disabled")
            .on("click.tabsGuard", function (e) {
                e.preventDefault();
                $.wn.flashMsg({
                    text: "Сохраните каталог, чтобы открыть эту вкладку",
                    class: "warning",
                });
                return false;
            });
    }

    function enableTabs() {
        var $tabs = $(".nav-tabs li:not(:first-child) a");
        $tabs
            .removeClass("disabled")
            .off("click.tabsGuard")
            .removeData("tabsGuardBound");
    }

    function refreshTabs(source) {
        var newModel = isNewCatalog();

        if (newModel) {
            disableTabs();
        } else {
            enableTabs();
        }
    }

    $(document).on("render", function () {
        refreshTabs("render");
    });

    // Initial check on DOM ready
    $(function () {
        refreshTabs("domready");
    });

    // After AJAX save, when id appears, re-enable tabs without reload
    $(document).on("ajaxSuccess", function () {
        // small delay to let form markup update
        setTimeout(function () {
            refreshTabs("ajaxSuccess");
        }, 50);
    });
})(window.jQuery);
