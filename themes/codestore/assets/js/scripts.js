document.addEventListener("DOMContentLoaded", () => {
    // ========================
    // Элементы уведомлений
    // ========================
    const buttonNotification = document.querySelector(
        ".header-notification__button"
    );
    const dropdownNotification = document.querySelector(
        ".header-notification__dropdown"
    );

    // ========================
    // Элементы мини-профиля
    // ========================
    const buttonMiniProfile = document.querySelector(".header-profile__avatar");
    const dropdownMiniProfile = document.querySelector(
        ".header-profile__dropdown"
    );

    let dropdownOpen = false; // статус открытия мини-профиля

    // ========================
    // Функция переключения dropdown (уведомления)
    // ========================
    function toggleDropdown(button, dropdown) {
        const isActive = button.classList.contains("active");

        // Закрываем все dropdown
        buttonNotification?.classList.remove("active");
        dropdownNotification?.classList.remove("active");
        buttonMiniProfile?.classList.remove("active");
        dropdownMiniProfile?.classList.remove("active");

        // Если текущий не активен — открываем
        if (!isActive) {
            button.classList.add("active");
            dropdown.classList.add("active");
        }
    }

    // ========================
    // Обработчики уведомлений
    // ========================
    if (buttonNotification && dropdownNotification) {
        buttonNotification.addEventListener("click", () =>
            toggleDropdown(buttonNotification, dropdownNotification)
        );
    }

    // ========================
    // Функции открытия/закрытия мини-профиля
    // ========================
    function openProfileDropdown() {
        buttonMiniProfile?.classList.add("active");
        dropdownMiniProfile?.classList.add("active");
        dropdownOpen = true;
    }

    function closeProfileDropdown() {
        buttonMiniProfile?.classList.remove("active");
        dropdownMiniProfile?.classList.remove("active");
        dropdownOpen = false;
    }

    if (buttonMiniProfile) {
        buttonMiniProfile.addEventListener("click", () => {
            if (dropdownOpen) closeProfileDropdown();
            else openProfileDropdown();
        });
    }

    // ========================
    // Закрытие dropdown при клике вне
    // ========================
    document.addEventListener("click", (e) => {
        if (dropdownMiniProfile && buttonMiniProfile) {
            if (
                !dropdownMiniProfile.contains(e.target) &&
                !buttonMiniProfile.contains(e.target)
            ) {
                closeProfileDropdown();
            }
        }
        if (dropdownNotification && buttonNotification) {
            if (
                !dropdownNotification.contains(e.target) &&
                !buttonNotification.contains(e.target)
            ) {
                dropdownNotification.classList.remove("active");
                buttonNotification.classList.remove("active");
            }
        }
    });

    // ========================
    // Обработка ссылок внутри мини-профиля
    // ========================
    dropdownMiniProfile?.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", (e) => {
            if (link.dataset.request) {
                e.preventDefault();
                $(link).request();
            }
            closeProfileDropdown();
        });
    });

    // ========================
    // Закрытие по клавише ESC
    // ========================
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeProfileDropdown();
            dropdownNotification?.classList.remove("active");
            buttonNotification?.classList.remove("active");
            searchOverlay?.classList.remove("active"); // скрываем поиск по ESC
            searchBox?.classList.remove("active"); // скрываем поиск по ESC
        }
    });

    // ========================
    // Логика табов
    // ========================
    document.querySelectorAll(".tab").forEach((tab) => {
        const buttonsTab = tab.querySelectorAll(".tab-button");
        const contentsTab = tab.querySelectorAll(".tab-content");

        buttonsTab.forEach((button) => {
            button.addEventListener("click", () => {
                const tabId = button.dataset.tab;
                buttonsTab.forEach((btn) => btn.classList.remove("is-active"));
                contentsTab.forEach((content) =>
                    content.classList.remove("is-active")
                );
                button.classList.add("is-active");
                const activeContent = tab.querySelector(`#tab-${tabId}`);
                if (activeContent) activeContent.classList.add("is-active");
            });
        });
    });

    //=========================
    // Вызов модального окна (Поиск)
    //=========================

    const btnSearchOpen = document.getElementById("btnSearchOpen");
    const searchModal = document.getElementById("searchModal");

    btnSearchOpen.addEventListener("click", (e) => {
        e.currentTarget.blur();
        Modal.open({
            title: "Что будем искать?",
            content: searchModal.innerHTML,
            focus: "searchInput",
        });
    });

    // ========================
    // Подсказки для елементов
    // ========================
    const tooltip = document.createElement("div");
    tooltip.className = "tooltip";
    document.body.appendChild(tooltip);

    document.querySelectorAll("[data-tooltip]").forEach((el) => {
        el.addEventListener("mouseenter", () => {
            tooltip.textContent = el.dataset.tooltip;

            const pos = el.dataset.tooltipPos || "top";
            tooltip.dataset.tooltipPos = pos;

            // Показываем временно, чтобы браузер вычислил размеры
            tooltip.style.opacity = "0";
            tooltip.style.display = "block";

            const rect = el.getBoundingClientRect();
            const margin = 8;
            let top, left;

            switch (pos) {
                case "bottom":
                    top = rect.bottom + margin;
                    left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2;
                    break;
                case "left":
                    top = rect.top + rect.height / 2 - tooltip.offsetHeight / 2;
                    left = rect.left - tooltip.offsetWidth - margin;
                    break;
                case "right":
                    top = rect.top + rect.height / 2 - tooltip.offsetHeight / 2;
                    left = rect.right + margin;
                    break;
                default: // top
                    top = rect.top - tooltip.offsetHeight - margin;
                    left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2;
            }

            tooltip.style.top = `${top}px`;
            tooltip.style.left = `${left}px`;
            tooltip.style.opacity = "1"; // теперь плавно показываем
        });

        el.addEventListener("mouseleave", () => {
            tooltip.style.opacity = "0";
        });
    });
});
