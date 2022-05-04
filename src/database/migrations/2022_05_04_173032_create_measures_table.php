<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use \App\Traits\RLSMigrationTrait;

    protected $table = "measures";

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->uuid('user');
            $table->uuid('supply');

            $table->date("date")->nullable();
            $table->string("hour")->nullable();
            $table->integer("hourCCH")->nullable();
            $table->dateTime("startAt")->nullable();
            $table->dateTime("endAt")->nullable();
            $table->boolean("invoiced")->default(false);
            $table->boolean("real")->default(true);
            $table->double("value");
            $table->string("obtainingMethod")->default("R");

            //FK's
            $table->foreign('user')->references('id')->on('users');
            $table->foreign('supply')->references('id')->on('supplies');

            $table->timestamps();
        });

        $this->RLSMigrationTrait($this->table);

    }

    public function down()
    {
        Schema::dropIfExists($this->table);

    }
};
