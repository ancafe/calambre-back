<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $table = "contracts";

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid("id")->primary();


            $table->uuid('user');
            $table->uuid('supply');
            $table->uuid('company');

            $table->text('atrId')->nullable();
            $table->text('atrNumContract')->nullable();
            $table->text('status')->nullable();
            $table->integer('status_code')->nullable()->default(3);

            $table->decimal('P1', 3, 2)->nullable();
            $table->decimal('P2', 3, 2)->nullable();
            $table->decimal('P3', 3, 2)->nullable();
            $table->decimal('P4', 3, 2)->nullable();
            $table->decimal('P5', 3, 2)->nullable();
            $table->decimal('P6', 3, 2)->nullable();

            //FK's
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('supply')->references('id')->on('supplies');
            $table->foreign('company')->references('id')->on('companies');

            $table->timestamps();
        });

        DB::statement('GRANT ALL ON TABLE public.' . $this->table . ' TO "RLS_Users";');


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
