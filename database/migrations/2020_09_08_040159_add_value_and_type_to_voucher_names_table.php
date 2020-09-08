<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueAndTypeToVoucherNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucher_names', function (Blueprint $table) {
            $table->integer('value');
            $table->enum('type', ['virtual_currency', 'percentage']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_names', function (Blueprint $table) {
            $table->dropColumn('value');
            $table->dropColumn('type');
        });
    }
}
