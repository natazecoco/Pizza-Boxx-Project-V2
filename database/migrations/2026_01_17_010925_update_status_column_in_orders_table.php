<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            // Kita ubah menjadi string dengan panjang 50 agar aman untuk status apapun ke depannya
            $blueprint->string('status', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            // Kembalikan ke ukuran semula jika diperlukan (misal 20)
            $blueprint->string('status', 20)->change();
        });
    }
};