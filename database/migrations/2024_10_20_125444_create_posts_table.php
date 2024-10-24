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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('job_title_id');
            $table->string('post_title');
            $table->string('location');
            $table->longText('overview');
            $table->longText('responsabilities');
            $table->longText('requirements');
            $table->longText('skills');
            $table->unsignedInteger('experience_year');
            $table->enum('employment_type', ['work_from_home', 'full_time', 'remote', 'contract'])->comment('work_from_home, full_time, remote, contract');
            $table->enum('level_type', ['junior', 'middle', 'senior', 'head'])->comment('junior, middle, senior, head');
            $table->string('salary');
            $table->integer('total_applied')->default(0);
            $table->timestamps();

            $table->foreign('company_id')->on('users')->references('id');
            $table->foreign('job_title_id')->on('job_title')->references('id');

            $table->index('post_title');
        });


        Schema::create('post_applies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('post_id')->on('posts')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
