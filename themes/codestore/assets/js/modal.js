/* Вызов модального окна

    const btnSearchOpen = document.getElementById("btnSearchOpen");
    const searchModal = document.getElementById("searchModal");

    btnSearchOpen.addEventListener("click", (e) => {
        e.currentTarget.blur();
        Modal.open({
            title: "Заголовок",
            content: searchModal.innerHTML,
            focus: "searchInput",
            actions: [
                { label: "Отмена", variant: "ghost" },
                {
                    label: "OK",
                    variant: "primary",
                    onClick: () => console.log("OK"),
                },
            ],
        });
    });

*/

(function (root) {
    "use strict";

    const modal = document.querySelector(".modal-overlay");
    if (!modal) return;

    const windowEl = modal.querySelector(".modal-window");
    const titleEl = modal.querySelector(".modal-title");
    const contentEl = modal.querySelector(".modal-content");
    const footerEl = modal.querySelector(".modal-footer");
    const closeBtn = modal.querySelector(".modal-close");

    let isOpen = false;
    let lastActiveElement = null;

    const open = ({ title, content, actions } = {}) => {
        if (isOpen) return;

        lastActiveElement = document.activeElement;
        lastActiveElement?.blur?.();

        // Заголовок
        titleEl.textContent = title || "";
        titleEl.parentElement?.classList.toggle("has-title", !!title);

        // Контент
        contentEl.innerHTML = "";
        if (content instanceof Node) {
            contentEl.appendChild(content.cloneNode(true));
        } else if (content) {
            contentEl.innerHTML = content;
        }

        // Действия
        footerEl.innerHTML = "";
        if (Array.isArray(actions) && actions.length) {
            footerEl.style.display = "";
            actions.forEach((action) => {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.className = `ui-core-button is-${
                    action.variant || "primary"
                }`;
                btn.textContent = action.label || "Action";
                btn.addEventListener("click", () => {
                    action.onClick?.();
                    if (action.dismiss !== false) close();
                });
                footerEl.appendChild(btn);
            });
        } else {
            footerEl.style.display = "none";
        }

        // Открываем модалку
        modal.classList.add("active");
        document.body.classList.add("ui-core-modal-open");

        // --- автофокус один раз, как в поиске ---
        setTimeout(() => {
            const input = modal.querySelector("input, textarea, select");
            if (input) input.focus();
        }, 50);

        isOpen = true;
    };

    const close = () => {
        if (!isOpen) return;

        // Убираем активный класс — анимация закрытия
        modal.classList.remove("active");
        document.body.classList.remove("ui-core-modal-open");

        // Восстановление фокуса сразу
        lastActiveElement?.focus?.();
        isOpen = false;

        // Очистка контента и футера после завершения анимации
        const onTransitionEnd = (e) => {
            if (e.target === modal) {
                contentEl.innerHTML = "";
                footerEl.innerHTML = "";
                modal.removeEventListener("transitionend", onTransitionEnd);
            }
        };

        modal.addEventListener("transitionend", onTransitionEnd);
    };

    // События закрытия
    closeBtn?.addEventListener("click", close);
    modal.addEventListener("click", (e) => {
        if (e.target === modal) close();
    });
    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") close();
    });

    root.Modal = { open, close };
})(window);
