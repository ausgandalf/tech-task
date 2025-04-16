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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('surname');
            $table->string('phone');
            $table->string('country');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('selfie')->nullable();
            $table->string('introduction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('surname');
            $table->dropColumn('phone');
            $table->dropColumn('country');
            $table->dropColumn('gender');
            $table->dropColumn('selfie');
            $table->dropColumn('introduction');
        });
    }
};
