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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('title');
            $table->text('description')->nullable(); 
            $table->string('level'); 
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User yang diperingatkan
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade'); // Admin yang mengeluarkan peringatan (asumsi tabel admin juga 'users')
            $table->string('warning_type'); // Tipe peringatan (e.g., 'violation', 'account_action', 'announcement')
            $table->string('subject'); // Judul atau subjek peringatan
            $table->text('message'); // Isi pesan peringatan
            $table->enum('status', ['sent', 'read', 'resolved', 'pending_action'])->default('sent'); // Status peringatan
            $table->timestamp('expires_at')->nullable(); // Tanggal kadaluarsa (opsional)
            $table->timestamps(); // created_at dan updated_at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};