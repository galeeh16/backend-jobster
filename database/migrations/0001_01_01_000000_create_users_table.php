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
        // Base User
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->enum('user_type', ['applier', 'company'])->nullable()->comment('applier, company');
            $table->timestamps();

            $table->index('email');
        });

        // User Profile
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_title_id')->nullable();
            $table->string('location')->nullable();
            $table->string('full_address')->nullable();
            $table->string('about_me')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv')->nullable();
            $table->string('portfolio')->nullable();
            $table->unsignedInteger('experience_year')->nullable();
            $table->boolean('availibility_for_work')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('job_title_id')->references('id')->on('job_title');
        });

        // Company Profile
        Schema::create('company_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            // $table->string('company_name');
            $table->string('address');
            $table->string('location');
            $table->text('about_company')->nullable();
            $table->unsignedInteger('company_size');
            $table->date('founded_in');
            $table->string('photo')->nullable();
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linked_in_url')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
