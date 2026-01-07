(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.loader = factory;
})(window, function () {
    "use strict";

    let overlay = null;
    let runningRequests = 0;

    const ensureOverlay = () => {
        if (overlay) {
            return overlay;
        }

        overlay = document.createElement("div");
        overlay.className = "ui-core-loader";
        overlay.innerHTML = '<div class="ui-core-loader__spinner"></div>';
        document.body.appendChild(overlay);
        return overlay;
    };

    const toggle = () => {
        if (!overlay) {
            return;
        }

        if (runningRequests > 0) {
            overlay.classList.add("is-active");
        } else {
            overlay.classList.remove("is-active");
        }
    };

    if (document.body) {
        ensureOverlay();
    } else {
        document.addEventListener("DOMContentLoaded", ensureOverlay, {
            once: true,
        });
    }

    return {
        show() {
            runningRequests += 1;
            ensureOverlay();
            toggle();
        },
        hide() {
            runningRequests = Math.max(0, runningRequests - 1);
            toggle();
        },
        reset() {
            runningRequests = 0;
            toggle();
        },
        isVisible() {
            return runningRequests > 0;
        },
    };
});
