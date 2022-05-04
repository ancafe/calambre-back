<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait RLSMigrationTrait
{

    public function RLSMigrationTrait($table)
    {
        DB::statement('GRANT ALL ON TABLE public.' . $table . ' TO "RLS_Users";');
        DB::statement('ALTER TABLE public.' . $table . ' ENABLE ROW LEVEL SECURITY;');
        DB::statement('CREATE POLICY user_isolated ON public.' . $table . '
	        AS PERMISSIVE
	        FOR ALL
            TO public
	        USING('.
            $table . '.user::TEXT = current_user
	        );
	    ');
    }


}
