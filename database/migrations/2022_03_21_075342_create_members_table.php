<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('title', 10);
            $table->string('customer_id', 50)->unique();
            $table->string('civil_status', 20);
            $table->string('gender', 10);
            $table->string('full_name', 191);
            $table->string('name_with_initial', 191);
            $table->string('nic', 20)->unique();
            $table->date('dob');
            $table->string('contact_number', 15);
            $table->string('mobile1', 15);
            $table->string('mobile2', 15)->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('address1', 191);
            $table->string('address2', 191);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip', 20);
            $table->string('photo', 191)->nullable();
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
        Schema::dropIfExists('members');
    }
}
