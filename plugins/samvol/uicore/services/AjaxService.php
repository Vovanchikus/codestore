<?php namespace Samvol\UiCore\Services;

use ApplicationException;
use Backend\Facades\BackendAuth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Samvol\UiCore\Classes\AjaxResponse;
use Samvol\UiCore\Classes\UiEvents;
use Winter\Storm\Exception\ValidationException;

class AjaxService
{
    /**
     * Run the given callback within a guarded AJAX pipeline.
     */
    public function handle(callable $callback, array $options = []): array
    {
        UiEvents::fire(UiEvents::AJAX_BEFORE, $options);

        try {
            $this->assertAuthorized($options);

            $result = App::call($callback);
            $message = $options['successMessage'] ?? '';
            $data = $result ?? [];

            UiEvents::fire(UiEvents::AJAX_AFTER, $data, $options);

            return AjaxResponse::success($data, $message);
        } catch (ValidationException $validationException) {
            return AjaxResponse::validationError($validationException->getErrors());
        } catch (AuthorizationException $authorizationException) {
            return AjaxResponse::forbidden($authorizationException->getMessage() ?: 'Access denied');
        } catch (ApplicationException $applicationException) {
            Log::warning('UiCore AJAX application exception', [
                'message' => $applicationException->getMessage(),
            ]);

            return AjaxResponse::error($applicationException->getMessage());
        } catch (\Throwable $throwable) {
            Log::error('UiCore AJAX failure', [
                'exception' => $throwable,
            ]);

            $message = $options['errorMessage'] ?? 'Internal server error';
            UiEvents::fire(UiEvents::AJAX_ERROR, $throwable, $options);

            return AjaxResponse::error($message);
        }
    }

    protected function assertAuthorized(array $options = []): void
    {
        $guard = $options['guard'] ?? null;
        $permission = $options['permission'] ?? null;
        $authRequired = (bool) ($options['auth'] ?? false);

        if (!$guard && !$permission && !$authRequired) {
            return;
        }

        if ($guard === 'backend' || $permission) {
            $user = BackendAuth::getUser();
            if (!$user) {
                throw new AuthorizationException('Backend authentication required');
            }

            if ($permission) {
                $permissions = is_array($permission) ? $permission : [$permission];
                foreach ($permissions as $code) {
                    if (!$user->hasAccess($code)) {
                        throw new AuthorizationException('Access denied');
                    }
                }
            }

            return;
        }

        if ($authRequired || $guard === 'frontend') {
            $guardName = $guard === 'frontend' ? 'web' : $guard;
            $user = $guardName ? Auth::guard($guardName)->user() : Auth::user();
            if (!$user) {
                throw new AuthorizationException('Authentication required');
            }
        }
    }
}
