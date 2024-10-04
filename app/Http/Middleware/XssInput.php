<?php

namespace App\Http\Middleware;

use Closure;
use Modules\User\Entities\ProfileChangedPass;
use ProtoneMedia\LaravelXssProtection\Events\MaliciousInputFound;
use ProtoneMedia\LaravelXssProtection\Middleware\XssCleanInput;
use TorMorten\Eventy\Facades\Events;
use Illuminate\Http\Request;
class XssInput extends XssCleanInput
{
    public function handle($request, Closure $next)
    {
        $this->sanitizedKeys = [];

        foreach (static::$skipCallbacks as $callback) {
            if ($callback($request)) {
                return $next($request);
            }
        }

        $dispatchEvent = $this->enabledInConfig('dispatch_event_on_malicious_input');

        if (count(static::$skipKeyCallbacks) > 0 || $dispatchEvent) {
            $this->originalRequest = clone $request;
        }

        $this->clean($request);

        if (count($this->sanitizedKeys) === 0) {
            return $next($request);
        }

        if ($dispatchEvent) {
            event(new MaliciousInputFound($this->sanitizedKeys, $this->originalRequest, $request));
        }

        if ($this->enabledInConfig('terminate_request_on_malicious_input')) {
            return response()->json([
                'status' => 'error',
                'message' => trans('app.message_xss_security')
            ], 200);
        }

        return $next($request);
    }
    private function enabledInConfig($key): bool
    {
        return (bool) config("xss-protection.middleware.{$key}");
    }
}
