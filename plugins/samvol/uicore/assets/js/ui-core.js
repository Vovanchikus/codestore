(function (root) {
    'use strict';

    const modules = root.SamvolUiCoreModules || {};
    const Snowboard = root.Snowboard;

    const createBus = () => {
        if (typeof EventTarget === 'function') {
            return new EventTarget();
        }
        return document.createElement('span');
    };

    const bus = createBus();
    const UI = root.UI || {};

    const buildEvent = (name, detail) => {
        if (typeof CustomEvent === 'function') {
            return new CustomEvent(name, { detail: detail || {} });
        }

        const fallback = document.createEvent('CustomEvent');
        fallback.initCustomEvent(name, false, false, detail || {});
        return fallback;
    };

    UI.version = '1.0.0';
    UI.events = UI.events || {
        on(event, callback) {
            bus.addEventListener(`uicore:${event}`, callback);
        },
        off(event, callback) {
            bus.removeEventListener(`uicore:${event}`, callback);
        },
        emit(event, detail) {
            bus.dispatchEvent(buildEvent(`uicore:${event}`, detail));
        },
    };

    Object.entries(modules).forEach(([name, factory]) => {
        if (typeof factory !== 'function') {
            return;
        }

        const moduleExport = factory(UI, Snowboard);
        if (moduleExport) {
            UI[name] = moduleExport;
        }
    });

    if (root.SamvolUiCoreModules) {
        delete root.SamvolUiCoreModules;
    }

    root.UI = UI;

    if (!Snowboard) {
        console.warn('[UiCore] Snowboard is not loaded. AJAX helpers were skipped.');
        return;
    }

    const stopLoader = () => {
        if (UI.loader) {
            UI.loader.hide();
        }
    };

    Snowboard.on('ajaxBeforeSend', (requestInstance) => {
        UI.events.emit('snowboard:ajaxBeforeSend', { request: requestInstance });
        if (UI.loader) {
            UI.loader.show();
        }
    });

    Snowboard.on('ajaxStart', (promise, requestInstance) => {
        UI.events.emit('snowboard:ajaxStart', { promise, request: requestInstance });
    });

    Snowboard.on('ajaxDone', (response, request) => {
        stopLoader();
        UI.events.emit('snowboard:ajaxDone', { response, request });
    });

    Snowboard.on('ajaxError', (error, request) => {
        stopLoader();
        UI.events.emit('snowboard:ajaxError', { error, request });

        if (UI.toast) {
            const message = (error && (error.message || error.X_WINTER_ERROR_MESSAGE)) || 'Unexpected error';
            UI.toast.error(message);
        }
    });

    Snowboard.on('ajaxErrorMessage', (message, request) => {
        UI.events.emit('snowboard:ajaxErrorMessage', { message, request });
        if (UI.toast) {
            UI.toast.error(message);
            return false;
        }
        return true;
    });

    Snowboard.on('ajaxSuccess', (response, request) => {
        UI.events.emit('snowboard:ajaxSuccess', { response, request });
    });
})(window);
