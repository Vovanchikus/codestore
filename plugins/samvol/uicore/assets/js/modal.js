(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.modal = factory(root.SamvolUiCoreModules);
})(window, function (UI) {
    "use strict";

    // ===============================
    // DOM
    // ===============================

    const modal = document.querySelector("modal");
    if (!modal) {
        console.warn("[UiCore Modal] #modal-container not found");
        return {};
    }

    const windowEl = modal.querySelector(".modal-window");
    const headerEl = modal.querySelector(".modal-header");
    const titleEl = modal.querySelector(".modal-title");
    const contentEl = modal.querySelector(".modal-content");
    const footerEl = modal.querySelector(".modal-footer");
    const closeBtn = modal.querySelector(".modal-close");

    let isOpen = false;

    // ===============================
    // Helpers
    // ===============================

    const setTitle = (title) => {
        if (!title) {
            headerEl.hidden = true;
            titleEl.textContent = "";
            return;
        }

        titleEl.textContent = title;
        headerEl.hidden = false;
    };

    const setContent = (content) => {
        contentEl.innerHTML = "";

        if (!content) return;

        if (typeof content === "string") {
            contentEl.textContent = content;
            return;
        }

        if (content instanceof Node) {
            contentEl.appendChild(content);
            return;
        }

        contentEl.textContent = String(content);
    };

    const renderActions = (actions) => {
        footerEl.innerHTML = "";

        if (!Array.isArray(actions) || actions.length === 0) {
            footerEl.hidden = true;
            return;
        }

        footerEl.hidden = false;

        actions.forEach((action) => {
            const btn = document.createElement("button");

            btn.type = "button";
            btn.className = `ui-core-button is-${action.variant || "primary"}`;
            btn.textContent = action.label || "Action";

            btn.addEventListener("click", () => {
                if (typeof action.onClick === "function") {
                    action.onClick();
                }

                if (action.dismiss !== false) {
                    close();
                }
            });

            footerEl.appendChild(btn);
        });
    };

    // ===============================
    // Open / Close
    // ===============================

    const open = (content, options = {}) => {
        if (isOpen) close();

        setTitle(options.title);
        setContent(content);
        renderActions(options.actions);

        modal.hidden = false;
        document.body.classList.add("ui-core-modal-open");

        isOpen = true;
    };

    const close = () => {
        if (!isOpen) return;

        modal.classList.add("is-closing");

        const cleanup = () => {
            modal.classList.remove("is-closing");
            modal.hidden = true;

            contentEl.innerHTML = "";
            footerEl.innerHTML = "";
            footerEl.hidden = true;

            document.body.classList.remove("ui-core-modal-open");
            isOpen = false;
        };

        modal.addEventListener("transitionend", cleanup, { once: true });

        setTimeout(cleanup, 300);
    };

    // ===============================
    // Events
    // ===============================

    closeBtn.addEventListener("click", close);

    modal.addEventListener("click", (e) => {
        if (e.target === modal) close();
    });

    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") close();
    });

    // ===============================
    // Public API
    // ===============================

    const api = {
        open,
        close,
    };

    // ===============================
    // confirm()
    // ===============================

    if (UI) {
        UI.confirm = (message, callback) => {
            const wrapper = document.createElement("div");
            wrapper.textContent = message;

            api.open(wrapper, {
                title: "Confirm",
                actions: [
                    {
                        label: "Cancel",
                        variant: "ghost",
                        onClick: () => callback(false),
                    },
                    {
                        label: "Confirm",
                        variant: "primary",
                        onClick: () => callback(true),
                    },
                ],
            });
        };
    }

    return api;
});
