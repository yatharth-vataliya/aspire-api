<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->float("amount_required", 12,4);
            $table->integer("loan_terms");
            $table->enum("loan_status", ["PENDING", "APPROVED", "REJECTED"])->default("PENDING");
            $table->enum("is_loan_paid", ["PENDING", "PAID"]);
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
