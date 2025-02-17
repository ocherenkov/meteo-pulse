<?php

use App\Models\UserPreference;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracking_weather_parameters', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserPreference::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('threshold', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_weather_parameters');
    }
};
