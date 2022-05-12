<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private string $table = 'hours';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->integer("id")->primary();
            $table->string("name")->unique();
        });

        DB::statement('GRANT ALL ON TABLE public.' . $this->table . ' TO "RLS_Users";');
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
