<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft');
            $table->date('valid_until');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proposals');
    }
};