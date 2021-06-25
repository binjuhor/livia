<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_line_items', function (Blueprint $table) {
            $table->id();
            $table->string('xero_id')->nullable();
            $table->foreignId('invoice_id')
                  ->constrained('invoices')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->string('description');
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedDecimal('unit_amount')->default(0);
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
            $table->dropForeign('invoice_line_items_invoice_id_foreign');
            $table->dropIfExists();
        });
    }
}
