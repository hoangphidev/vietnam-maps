<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVietnamMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $tableNames = config('vietnam-maps.tables');
        $columnNames = config('vietnam-maps.columns');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/vietnam-maps.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['provinces'], function (Blueprint $table) use ($columnNames) {
            $table->bigIncrements('id');
            $table->string($columnNames['name']);
            $table->string($columnNames['gso_id']);
            $table->timestamps();
        });

        Schema::create($tableNames['districts'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->bigIncrements('id');
            $table->string($columnNames['name']);
            $table->string($columnNames['gso_id']);
            $table->unsignedBigInteger($columnNames['province_id']);
            $table->timestamps();

            $table->foreign($columnNames['province_id'])
                ->references('id')
                ->on($tableNames['provinces'])
                ->cascadeOnDelete();
        });

        Schema::create($tableNames['wards'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->bigIncrements('id');
            $table->string($columnNames['name']);
            $table->string($columnNames['gso_id']);
            $table->unsignedBigInteger($columnNames['district_id']);
            $table->timestamps();

            $table->foreign($columnNames['district_id'])
                ->references('id')
                ->on($tableNames['districts'])
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function down()
    {
        $tableNames = config('vietnam-maps.tables');
        $columnNames = config('vietnam-maps.columns');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/vietnam-maps.php not found and defaults could not be merged.
             Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::table($tableNames['wards'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->dropForeign( $tableNames['wards'] . '_' . $columnNames['district_id'] . '_foreign');
        });

        Schema::table($tableNames['districts'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->dropForeign( $tableNames['districts'] . '_' . $columnNames['province_id'] . '_foreign');
        });

        Schema::dropIfExists($tableNames['provinces']);
        Schema::dropIfExists($tableNames['districts']);
        Schema::dropIfExists($tableNames['wards']);
    }
}
