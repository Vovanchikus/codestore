(function (root, factory) {
    root.SamvolUiCoreModules = root.SamvolUiCoreModules || {};
    root.SamvolUiCoreModules.request = factory;
})(window, function (UI, Snowboard) {
    "use strict";

    const normalizeError = (error) => {
        if (!error) {
            return "Unexpected error";
        }

        if (typeof error === "string") {
            return error;
        }

        if (error.message) {
            return error.message;
        }

        if (error.X_WINTER_ERROR_MESSAGE) {
            return error.X_WINTER_ERROR_MESSAGE;
        }

        if (Array.isArray(error.errors)) {
            return error.errors.join(", ");
        }

        if (error.errors && typeof error.errors === "object") {
            const messages = [];
            Object.values(error.errors).forEach((value) => {
                if (Array.isArray(value)) {
                    value.forEach((item) => messages.push(item));
                    return;
                }

                if (value) {
                    messages.push(String(value));
                }
            });

            if (messages.length > 0) {
                return messages.join(", ");
            }
        }

        return "Unexpected error";
    };

    const ensureSnowboard = () => {
        if (!Snowboard || typeof Snowboard.request !== "function") {
            throw new Error(
                "Snowboard.request is required. Include modules/system/assets/js/snowboard/request.js."
            );
        }
    };

    const emit = (event, detail) => {
        if (UI.events && typeof UI.events.emit === "function") {
            UI.events.emit(event, detail || {});
        }
    };

    const buildOptions = (options = {}) => {
        const safeOptions = { ...options };
        const userSuccess = safeOptions.success;
        const userError = safeOptions.error;
        const userComplete = safeOptions.complete;

        safeOptions.success = (response, requestInstance) => {
            emit("request:success", { response, request: requestInstance });

            if (
                typeof userSuccess === "function" &&
                userSuccess(response, requestInstance) === false
            ) {
                return false;
            }

            if (response && response.success === false) {
                if (UI.toast) {
                    UI.toast.error(
                        response.message || "Server rejected the request."
                    );
                }
                return false;
            }

            if (response && response.success && response.message && UI.toast) {
                UI.toast.success(response.message);
            }

            return true;
        };

        safeOptions.error = (errorResponse, requestInstance) => {
            emit("request:error", {
                error: errorResponse,
                request: requestInstance,
            });

            if (
                typeof userError === "function" &&
                userError(errorResponse, requestInstance) === false
            ) {
                return false;
            }

            if (UI.toast) {
                UI.toast.error(normalizeError(errorResponse));
            }

            return true;
        };

        safeOptions.complete = (response, requestInstance) => {
            emit("request:complete", { response, request: requestInstance });

            if (typeof userComplete === "function") {
                userComplete(response, requestInstance);
            }
        };

        safeOptions.handleErrorMessage = (message) => {
            if (UI.toast) {
                UI.toast.error(message);
            }
            return false;
        };

        return safeOptions;
    };

    const resolveElement = (element) => {
        if (!element || element instanceof Element) {
            return element || null;
        }

        if (typeof element === "string") {
            const node = document.querySelector(element);
            if (!node) {
                throw new Error(`Element not found for selector: ${element}`);
            }
            return node;
        }

        throw new Error("Element must be a selector, DOM node or null.");
    };

    const request = (element, handler, options = {}) => {
        ensureSnowboard();

        if (
            typeof element === "string" &&
            element.indexOf("on") === 0 &&
            handler === undefined
        ) {
            options = handler || {};
            handler = element;
            element = null;
        }

        if (typeof handler === "object") {
            options = handler;
            handler = element;
            element = null;
        }

        if (!handler || typeof handler !== "string") {
            throw new Error("AJAX handler name is required.");
        }

        const requestOptions = buildOptions({ ...options });
        const target = resolveElement(element);

        return Snowboard.request(target, handler, requestOptions);
    };

    return request;
});
