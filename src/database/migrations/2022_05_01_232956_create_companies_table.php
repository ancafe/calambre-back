<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    use \App\Traits\RLSMigrationTrait;

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

        $this->RLSMigrationTrait($this->table);

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
