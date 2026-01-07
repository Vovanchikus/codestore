(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.modal = factory;
})(window, function (UI) {
    "use strict";

    let activeModal = null;

    const buildMarkup = (options) => {
        const overlay = document.createElement("div");
        overlay.className = "ui-core-modal";
        overlay.setAttribute("role", "dialog");
        overlay.setAttribute("aria-modal", "true");

        const dialog = document.createElement("div");
        dialog.className = "ui-core-modal__dialog";

        if (options && options.title) {
            const title = document.createElement("div");
            title.className = "ui-core-modal__title";
            title.textContent = options.title;
            dialog.appendChild(title);
        }

        const body = document.createElement("div");
        body.className = "ui-core-modal__body";
        dialog.appendChild(body);

        const footer = document.createElement("div");
        footer.className = "ui-core-modal__footer";
        dialog.appendChild(footer);

        overlay.appendChild(dialog);
        return { overlay, body, footer };
    };

    const mountContent = (body, content) => {
        if (!content) {
            body.textContent = "";
            return;
        }

        if (typeof content === "string") {
            body.textContent = content;
            return;
        }

        if (content instanceof Node) {
            body.innerHTML = "";
            body.appendChild(content);
            return;
        }

        body.textContent = String(content);
    };

    const renderActions = (footer, actions) => {
        footer.innerHTML = "";

        if (!Array.isArray(actions) || actions.length === 0) {
            footer.style.display = "none";
            return;
        }

        footer.style.display = "";

        actions.forEach((action) => {
            const button = document.createElement("button");
            button.type = "button";
            button.className = `ui-core-button is-${
                action.variant || "primary"
            }`;
            button.textContent = action.label || "Action";
            button.addEventListener("click", () => {
                if (typeof action.onClick === "function") {
                    action.onClick();
                }
                if (action.dismiss !== false) {
                    closeModal();
                }
            });
            footer.appendChild(button);
        });
    };

    const trapFocus = (overlay) => {
        const focusable = overlay.querySelectorAll(
            "button, [href], input, select, textarea"
        );
        if (focusable.length === 0) {
            return;
        }

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        const handleTab = (event) => {
            if (event.key !== "Tab") {
                return;
            }

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        };

        overlay.addEventListener("keydown", handleTab);
    };

    const openModal = (content, options = {}) => {
        closeModal();

        const markup = buildMarkup(options);
        mountContent(markup.body, content);
        renderActions(markup.footer, options.actions);

        if (options.dismissible !== false) {
            markup.overlay.addEventListener("click", (event) => {
                if (event.target === markup.overlay) {
                    closeModal();
                }
            });
        }

        document.body.classList.add("ui-core-modal-open");
        document.body.appendChild(markup.overlay);
        trapFocus(markup.overlay);

        activeModal = markup.overlay;

        window.addEventListener("keydown", escListener);

        return markup.overlay;
    };

    const escListener = (event) => {
        if (event.key === "Escape") {
            closeModal();
        }
    };

    const closeModal = () => {
        if (!activeModal) {
            return;
        }

        window.removeEventListener("keydown", escListener);
        activeModal.classList.add("is-closing");

        const modalToRemove = activeModal;
        activeModal = null;

        modalToRemove.addEventListener(
            "transitionend",
            () => {
                if (modalToRemove.parentNode) {
                    modalToRemove.parentNode.removeChild(modalToRemove);
                }
                if (!document.querySelector(".ui-core-modal")) {
                    document.body.classList.remove("ui-core-modal-open");
                }
            },
            { once: true }
        );

        window.setTimeout(() => {
            if (modalToRemove.parentNode) {
                modalToRemove.parentNode.removeChild(modalToRemove);
                if (!document.querySelector(".ui-core-modal")) {
                    document.body.classList.remove("ui-core-modal-open");
                }
            }
        }, 300);
    };

    const api = {
        open(content, options) {
            return openModal(content, options);
        },
        close() {
            closeModal();
        },
    };

    if (UI) {
        UI.confirm = (message, callback) => {
            const onResolve = (result) => {
                if (typeof callback === "function") {
                    callback(result);
                }
            };

            api.open(message, {
                title: "Confirm",
                actions: [
                    {
                        label: "Cancel",
                        variant: "ghost",
                        onClick: () => onResolve(false),
                    },
                    {
                        label: "Confirm",
                        variant: "primary",
                        onClick: () => onResolve(true),
                    },
                ],
            });
        };
    }

    return api;
});
