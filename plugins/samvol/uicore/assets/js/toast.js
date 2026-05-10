(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.toast = factory;
})(window, function () {
    "use strict";

    const POSITIONS = ["top-right", "top-center", "bottom-right"];
    const pending = {};
    const containers = new Map();
    const timers = new WeakMap();

    function normalizePosition(position) {
        if (typeof position === "string" && POSITIONS.includes(position)) {
            return position;
        }

        return "top-right";
    }

    function queueToast(position, payload) {
        if (!pending[position]) {
            pending[position] = [];
        }

        pending[position].push(payload);
    }

    function flushQueue(position) {
        if (!pending[position] || pending[position].length === 0) {
            return;
        }

        const queued = pending[position].splice(0);
        queued.forEach((payload) => renderToast(payload));
    }

    function ensureContainer(position) {
        if (containers.has(position)) {
            return containers.get(position);
        }

        const createContainer = () => {
            const node = document.createElement("div");
            node.className = `ui-core-toast-stack is-${position}`;
            document.body.appendChild(node);
            containers.set(position, node);
            flushQueue(position);
        };

        if (document.body) {
            createContainer();
            return containers.get(position);
        }

        document.addEventListener("DOMContentLoaded", createContainer, {
            once: true,
        });

        return null;
    }

    function renderToast(payload) {
        if (!payload || !payload.message) {
            return;
        }

        const position = normalizePosition(payload.position);
        const stack = ensureContainer(position);

        if (!stack) {
            queueToast(position, payload);
            return;
        }

        const { message, variant, timeout } = payload;
        const tone = variant || "info";
        const toast = document.createElement("div");
        toast.className = `ui-core-toast is-${tone}`;
        toast.setAttribute("role", tone === "error" ? "alert" : "status");

        const messageNode = document.createElement("div");
        messageNode.className = "ui-core-toast__message";
        messageNode.textContent = message;
        toast.appendChild(messageNode);

        const closer = document.createElement("button");
        closer.className = "ui-core-toast__close";
        closer.type = "button";
        closer.setAttribute("aria-label", "Close");
        closer.innerHTML = "&times;";
        closer.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();
            dismiss(toast);
        });

        toast.appendChild(closer);
        stack.appendChild(toast);

        const lifetime = typeof timeout === "number" ? timeout : 4000;
        if (lifetime > 0) {
            startTimer(toast, lifetime);
            toast.addEventListener("mouseenter", () => pauseToast(toast));
            toast.addEventListener("mouseleave", () => resumeToast(toast));
        }
    }

    function dismiss(toast) {
        if (!toast) {
            return;
        }

        clearTimer(toast);

        toast.classList.add("is-hidden");
        const removeToast = () => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        };

        toast.addEventListener("animationend", removeToast, { once: true });
        window.setTimeout(removeToast, 400);
    }

    function show(message, variant = "info", options = {}) {
        if (!message) {
            return;
        }

        renderToast({
            message,
            variant,
            timeout: options.timeout,
            position: options.position,
        });
    }

    function startTimer(toast, lifetime) {
        const meta = {
            lifetime,
            remaining: lifetime,
            start: Date.now(),
            paused: false,
        };

        meta.timeoutId = window.setTimeout(() => dismiss(toast), lifetime);
        timers.set(toast, meta);
    }

    function clearTimer(toast) {
        const meta = timers.get(toast);
        if (!meta) {
            return;
        }

        window.clearTimeout(meta.timeoutId);
        timers.delete(toast);
    }

    function pauseToast(toast) {
        const meta = timers.get(toast);
        if (!meta || meta.paused) {
            return;
        }

        window.clearTimeout(meta.timeoutId);
        meta.remaining = Math.max(0, meta.lifetime - (Date.now() - meta.start));
        meta.paused = true;
        timers.set(toast, meta);
    }

    function resumeToast(toast) {
        const meta = timers.get(toast);
        if (!meta) {
            return;
        }

        if (meta.remaining <= 0) {
            dismiss(toast);
            return;
        }

        meta.paused = false;
        meta.start = Date.now();
        meta.timeoutId = window.setTimeout(
            () => dismiss(toast),
            meta.remaining
        );
        timers.set(toast, meta);
    }

    return {
        success(message, options) {
            show(message, "success", options);
        },
        error(message, options) {
            show(message, "error", options);
        },
        info(message, options) {
            show(message, "info", options);
        },
    };
});
