<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_asset', 20)->unique();
            $table->string('nama_asset');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('serial_number')->nullable();
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->enum('kondisi', ['baik', 'cukup', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->enum('status', ['available', 'borrowed', 'maintenance', 'broken', 'lost'])->default('available');
            $table->string('lokasi')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('foto_asset')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kode_asset');
            $table->index('status');
            $table->index('kondisi');
            $table->index('category_id');
            $table->index('location_id');
            $table->index(['status', 'kondisi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
