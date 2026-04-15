<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (!Schema::hasColumn('usuarios', 'correo')) {
                    $table->string('correo')->nullable()->after('rol');
                }
                if (!Schema::hasColumn('usuarios', 'fecha_alta')) {
                    $table->date('fecha_alta')->nullable()->after('correo');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                if (Schema::hasColumn('usuarios', 'fecha_alta')) {
                    $table->dropColumn('fecha_alta');
                }
                if (Schema::hasColumn('usuarios', 'correo')) {
                    $table->dropColumn('correo');
                }
            });
        }
    }
};
