<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('user');
            $table->text("edis_id");
            $table->text("cups")->comment("AKA Name, Title");
            $table->text("provisioning_address");
            $table->boolean("main")->default(false);

            //FK's
            $table->foreign('user')->references('id')->on('users');

            $table->timestamps();
        });

        DB::statement('GRANT ALL ON TABLE public.supplies TO "RLS_Users";');

    }

    public function down()
    {
        Schema::dropIfExists('supplies');
    }
}
