(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.toast = factory;
})(window, function () {
    'use strict';

    const pending = [];
    let container = null;

    const ensureContainer = () => {
        if (container) {
            return container;
        }

        const create = () => {
            container = document.createElement('div');
            container.id = 'ui-core-toast-stack';
            container.className = 'ui-core-toast-stack';
            document.body.appendChild(container);
            pending.splice(0).forEach((payload) => renderToast(payload));
        };

        if (document.body) {
            create();
            return container;
        }

        document.addEventListener('DOMContentLoaded', create, { once: true });
        return container;
    };

    const renderToast = (payload) => {
        if (!payload || !payload.message) {
            return;
        }

        if (!container) {
            pending.push(payload);
            ensureContainer();
            return;
        }

        const { message, variant, timeout } = payload;
        const toast = document.createElement('div');
        toast.className = `ui-core-toast is-${variant}`;
        toast.setAttribute('role', variant === 'error' ? 'alert' : 'status');
        toast.textContent = message;

        const closer = document.createElement('button');
        closer.className = 'ui-core-toast__close';
        closer.type = 'button';
        closer.setAttribute('aria-label', 'Close');
        closer.innerHTML = '&times;';
        closer.addEventListener('click', () => dismiss(toast));

        toast.appendChild(closer);
        container.appendChild(toast);

        const lifetime = typeof timeout === 'number' ? timeout : 4000;
        if (lifetime > 0) {
            window.setTimeout(() => dismiss(toast), lifetime);
        }
    };

    const dismiss = (toast) => {
        if (!toast) {
            return;
        }

        toast.classList.add('is-hidden');
        toast.addEventListener('transitionend', () => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, { once: true });
    };

    return function install() {
        const show = (message, variant = 'info', options = {}) => {
            if (!message) {
                return;
            }

            ensureContainer();
            renderToast({ message, variant, timeout: options.timeout });
        };

        return {
            success(message, options) {
                show(message, 'success', options);
            },
            error(message, options) {
                show(message, 'error', options);
            },
            info(message, options) {
                show(message, 'info', options);
            },
        };
    };
});
