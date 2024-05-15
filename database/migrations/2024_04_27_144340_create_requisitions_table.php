<?php

use App\Enums\RequisitionStatus;
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
        Schema::disableForeignKeyConstraints();

        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('reason', 100);
            $table->string('status')->default(RequisitionStatus::DRAFT);
            $table->dateTime('submitted_at')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('orderer_id')->nullable()->constrained('users');
            $table->string('account_num')->nullable();
            $table->dateTime('ordered_at')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_note', 100)->nullable();
            $table->json('items')->default('[]');
            $table->foreignId('user_id');
            $table->foreignId('vendor_id');
            $table->foreignId('year_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
