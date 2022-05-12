<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    private string $table = 'hour_period';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string("period_code",10);
            $table->foreign('period_code')->references('code')->on('period');

            $table->integer("hour_id");
            $table->foreign('hour_id')->references('id')->on('hours');

            $table->integer("weekday")->comment("0 for sunday");
            $table->boolean("holiday")->comment('0 no holiday. 1 in holiday');

            $table->unique(['hour_id', 'weekday', 'holiday']);
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
