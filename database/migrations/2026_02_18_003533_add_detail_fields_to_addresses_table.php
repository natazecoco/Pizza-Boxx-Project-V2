<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Nama Penerima (Bisa beda dengan nama akun)
            $table->string('receiver_name')->nullable()->after('user_id');
            
            // Alamat dari titik Map (Otomatis)
            $table->text('map_address')->nullable()->after('address');
            
            // Detail tambahan (Lantai, No Rumah, Pagar, dll - Input Manual)
            $table->text('detail_address')->nullable()->after('map_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['receiver_name', 'map_address', 'detail_address']);
        });
    }
};
