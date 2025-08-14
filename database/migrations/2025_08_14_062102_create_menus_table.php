
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Menu display name
            $table->string('slug')->unique();  // Unique slug for internal reference
            $table->string('icon')->nullable(); // Optional icon class (e.g., FontAwesome)
            $table->string('url')->nullable(); // Link or route
            $table->unsignedBigInteger('parent_id')->nullable(); // For submenus
            $table->integer('order')->default(0); // Sorting order
            $table->boolean('is_active')->default(true); // Enable/Disable menu
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
