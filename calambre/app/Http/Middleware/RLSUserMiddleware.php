<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RLSUserMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()) {
            //logged --> RLS
            $userUUID = auth()->payload()->get('id');
            Config::set("database.connections.pgsql.username", $userUUID);
            Config::set("database.connections.pgsql.password", auth()->user()->getAuthPassword());
            Schema::connection('pgsql')->getConnection()->reconnect();
            return $next($request);

        }
        return $next($request);
    }
}
