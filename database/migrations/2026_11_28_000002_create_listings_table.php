<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One table for produce, livestock and service listings. The seller "Create Listing"
     * design uses a single shared form that only swaps two fields by type, so the three
     * types share their columns instead of getting three near-identical tables.
     */
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            // Restrict: a category that still has listings cannot be deleted.
            $table->foreignId('category_id')->nullable()->constrained('categories')->restrictOnDelete();
            $table->string('type', 20);
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(0);

            // Produce
            $table->string('unit', 50)->nullable();
            $table->date('harvest_date')->nullable();
            // Livestock
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('purpose')->nullable();
            // Service
            $table->string('service_area')->nullable();
            $table->string('availability')->nullable();

            $table->string('location')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
