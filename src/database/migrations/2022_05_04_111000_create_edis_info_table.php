<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected $table = "edis";

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('user');
            $table->text('username')->nullable()->comment('EDIS Login User');
            $table->text('password')->nullable()->comment('EDIS Login Password');
            $table->text('name')->nullable();
            $table->text("visibility")->nullable();

            //FK's
            $table->foreign('user')->references('id')->on('users');

            $table->timestamps();
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

    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
