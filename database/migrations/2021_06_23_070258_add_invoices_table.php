<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('xero_id')->nullable();
            $table->string('reference');
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedDecimal('total')->default(0);
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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_project_id_foreign');
            $table->dropIfExists();
        });
    }
}
