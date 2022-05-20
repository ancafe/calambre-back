<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = DB::select("
            SELECT usename from pg_user
                JOIN pg_auth_members on (pg_user.usesysid=pg_auth_members.member)
                JOIN pg_roles on (pg_roles.oid=pg_auth_members.roleid)
            WHERE pg_roles.rolname='RLS_Users';");

        foreach($users as $user){
            DB::statement('DROP USER "'. $user->usename . '";');
        }

        DB::statement('DROP ROLE IF EXISTS "RLS_Users";');
        DB::statement('CREATE ROLE "RLS_Users" WITH NOLOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
