<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Categories were only a hard-coded <select> in the seller form. A table is needed so
     * categories can be managed and so listings can reference them with a foreign key.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->string('name');
            $table->string('slug');
            $table->timestamps();

            $table->unique(['type', 'name']);
            $table->unique(['type', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
