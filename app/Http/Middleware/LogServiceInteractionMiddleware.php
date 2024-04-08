<?php

namespace App\Http\Middleware;

use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

use Closure;

class LogServiceInteractionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $requestData = $request->all();

        if (isset($requestData['password'])) {
            $requestData['password'] = Hash::make($requestData['password']);
        }

        $log = new ServiceLog();
        $log->user_id = Auth::id() ?? null;
        $log->service = $request->url();
        $log->request_body = json_encode($requestData);
        $log->response_code = $response->getStatusCode();
        $log->response_body = json_encode($response->getContent());
        $log->ip_address = $request->ip();
        $log->save();

        return $response;
    }
}
