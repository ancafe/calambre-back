<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use \App\Traits\RLSMigrationTrait;

    private $table = 'supplies';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
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

        $this->RLSMigrationTrait($this->table);



    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
