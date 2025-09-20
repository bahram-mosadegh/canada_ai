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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('client_full_name')->nullable();
            $table->string('client_mobile')->nullable();
            $table->integer('number_of_applicants')->default(0);
            $table->json('applicant_names')->nullable();
            $table->string('type_of_visa')->nullable();
            $table->boolean('is_married')->default(false);
            $table->boolean('is_azmayeshgahdaran')->default(false);
            $table->boolean('is_bazneshastegan')->default(false);
            $table->boolean('is_dowlatcarmand')->default(false);
            $table->boolean('is_karfarmayansherkat')->default(false);
            $table->boolean('is_mashaghelazad')->default(false);
            $table->boolean('is_mojeran')->default(false);
            $table->boolean('is_pezeshkdarayematab')->default(false);
            $table->boolean('is_pezeshkdarbimarestan')->default(false);
            $table->boolean('is_sakhtemansazan')->default(false);
            $table->boolean('is_vokalayedarayedaftar')->default(false);
            $table->boolean('is_vokalayeshagheldaftar')->default(false);
            $table->boolean('is_business')->default(false);
            $table->json('required_documents')->nullable();
            $table->string('status')->default('in_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_cases');
    }
};


