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
        Schema::create('worksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId("advisor_id")->index();
            $table->foreignId("mechanic_id")->nullable()->index();
            $table->string("plate");
            $table->string("make");
            $table->string("type");
            $table->string("owner_name");
            $table->string("owner_address");
            $table->boolean("closed")->default(false);
            $table->enum("paid_with", ["cash", "card"])->nullable();
            $table->timestamps();
        });

        Schema::create('worksheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("worksheet_id");
            $table->foreignId("item_id");
            $table->integer("quantity");
        });

        Schema::create('available_items', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ["procedure", "material", "part"])->index();
            $table->string("nice_name");
            $table->integer("price");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worksheets');
        Schema::dropIfExists('worksheet_items');
        Schema::dropIfExists('available_items');
    }
};
