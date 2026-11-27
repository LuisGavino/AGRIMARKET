<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Orders do not exist yet. Items snapshot the name and price so history stays correct
     * after a listing changes, and the status history table keeps every status change.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Restrict: a user with orders cannot be deleted, so order history is preserved.
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('status', 20)->default('pending')->index();
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('listing_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
