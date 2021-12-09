<?php

use HDSSolutions\Laravel\Blueprints\BaseBlueprint as Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePosTable extends Migration {

    public function up() {
        // get schema builder
        $schema = DB::getSchemaBuilder();

        // replace blueprint
        $schema->blueprintResolver(fn($table, $callback) => new Blueprint($table, $callback));

        // create table
        $schema->create('pos', function(Blueprint $table) {
            $table->id();
            $table->foreignTo('Company');
            $table->string('name');
            $table->foreignTo('Currency');
            $table->foreignTo('Branch');
            $table->foreignTo('Warehouse');
            $table->foreignTo('CashBook');
            $table->foreignTo('Stamping');
            $table->foreignTo('Customer');
            $table->foreignTo('PriceList');
            $table->string('prepend')->nullable();
            $table->unsignedTinyInteger('length');
            $table->unsignedBigInteger('start');
            $table->unsignedBigInteger('end');
            $table->unsignedBigInteger('current')->nullable();
        });

        $schema->create('pos_employee', function(Blueprint $table) {
            $table->asPivot();
            $table->foreignTo('pos', 'pos_id');
            $table->foreignTo('Employee');
            $table->primary([ 'pos_id', 'employee_id' ]);
        });
    }

    public function down() {
        Schema::dropIfExists('pos_employee');
        Schema::dropIfExists('pos');
    }

}
