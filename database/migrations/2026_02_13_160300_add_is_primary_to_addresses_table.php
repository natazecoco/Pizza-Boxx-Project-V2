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
        Schema::table('addresses', function (Blueprint $table) {
            // Menambahkan kolom is_primary setelah kolom label
            $table->boolean('is_primary')->default(false)->after('label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
