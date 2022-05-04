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
