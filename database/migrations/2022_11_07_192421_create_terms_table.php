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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("admin_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("loan_id");
            $table->date("term_pay_date");
            $table->float("term_amount", 15, 5);
            $table->float("term_amount_paid", 15,5)->default(0);
            $table->enum("payment_status", ["PENDING", "PAID"])->default("PENDING");
            $table->foreign("loan_id")->references("id")->on("loans")->onDelete("CASCADE");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms');
    }
};
