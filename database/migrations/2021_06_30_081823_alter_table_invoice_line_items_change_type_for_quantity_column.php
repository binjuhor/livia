<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInvoiceLineItemsChangeTypeForQuantityColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_line_items', function (Blueprint $table) {
            $table->unsignedDecimal('quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_line_items', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->change();
        });
    }
}
