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
        Schema::table('received_mails', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0);
            $table->foreignId('email_id');
            $table->longText('logs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('received_mails', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('email_id');
            $table->dropColumn('logs');
        });
    }
};
