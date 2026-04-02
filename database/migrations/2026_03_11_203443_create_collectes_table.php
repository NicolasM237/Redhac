<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers natures
            $table->foreignId('nature_id')
                ->constrained('natures')
                ->onDelete('cascade');

            $table->string('nom')->unique();
            // $table->integer('quantite');
            $table->date('date_collecte');

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
        Schema::dropIfExists('collectes');
    }
}
