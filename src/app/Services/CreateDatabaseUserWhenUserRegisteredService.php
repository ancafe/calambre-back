<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateDatabaseUserWhenUserRegisteredService
{

    public function create(string $user, string $password)
    {
        $sql = '
        CREATE ROLE "' . $user. '" WITH
        LOGIN
        NOSUPERUSER
        INHERIT
        NOCREATEDB
        NOCREATEROLE
        NOREPLICATION
        PASSWORD \'' . $password. '\'
        ';

        DB::statement($sql);
        DB::statement('
        GRANT "RLS_Users" TO "' . $user. '";
        ');

        Log::info("Created user $user in database with password $password");
    }

}
