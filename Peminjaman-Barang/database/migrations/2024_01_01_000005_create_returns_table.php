<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('borrowing_id')->constrained('borrowings')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->date('return_date');
            $table->enum('overall_condition', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->text('notes')->nullable();
            $table->string('proof_image')->nullable();
            $table->decimal('late_fine', 15, 2)->default(0);
            $table->integer('late_days')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
