<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SetDatabaseConnection
{

    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        if ($user) {
            $organization = $user->organization;
            if ($organization) {
                Config::set('database.connections.tenant.database', $organization->database);
                DB::purge('tenant');
                DB::reconnect('tenant');
                DB::setDefaultConnection('tenant');
            }
        }

        return $next($request);
    }
}
