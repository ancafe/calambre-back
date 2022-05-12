<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private string $table = 'period';


    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->string("code", 10)->primary();
            $table->string("name");
        });

        DB::statement('GRANT ALL ON TABLE public.' . $this->table . ' TO "RLS_Users";');

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
