<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('method', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('method', ['transfer','cod','ewallet'])->change();
        });
    }
};
