<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    protected string $table = 'public_holidays';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->date("date")->primary();
            $table->string("name", 50);
        });

        DB::statement('GRANT ALL ON TABLE public.' . $this->table . ' TO "RLS_Users";');
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
};
