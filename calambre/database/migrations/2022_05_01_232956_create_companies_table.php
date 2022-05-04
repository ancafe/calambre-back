<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $table = "companies";

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text("companyId")->comment("internal ID");
            $table->text("name");
            $table->timestamps();
            $table->uuid('user')->nullable()->comment('If user not defined; global company');

            //FK's
            $table->foreign('user')->references('id')->on('users');
        });

        DB::statement('GRANT ALL ON TABLE public.'.$this->table.' TO "RLS_Users";');

        DB::statement('ALTER TABLE public.' . $this->table . ' ENABLE ROW LEVEL SECURITY;');

        DB::statement('CREATE POLICY user_isolated ON public.' . $this->table . '
	        AS PERMISSIVE
	        FOR ALL
            TO public
	        USING('.
                $this->table . '.user::TEXT = current_user
	        );
	    ');

        DB::statement('CREATE POLICY is_null ON public.' . $this->table . '
	        AS PERMISSIVE
	        FOR ALL
            TO public
	        USING('.
                $this->table . '.user IS NULL
	        );
	    ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
