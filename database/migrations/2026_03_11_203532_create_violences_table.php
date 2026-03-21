<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViolencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('violences', function (Blueprint $table) {
            $table->id();

            // Wizard Service
            $table->string('code')->unique();
            $table->boolean('permis')->default(false);
            $table->string('status');
            $table->string('contact');
            $table->string('occupation');
            $table->string('age');
            $table->string('sexe');
            $table->string('nationalite');

            // Wizard Time
            $table->string('residence');
            $table->date('datesurvenue');
            $table->string('lieusurvenue');
            $table->string('situation');
            $table->string('auteurs');

            // Wizard Details (Relations et Textes)
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('nature_id')->constrained('natures');
            $table->foreignId('collecte_id')->constrained('collectes');
            $table->text('description_cas');
            $table->text('mesure_obc');
            $table->text('risque_victime');
            $table->text('attente_victime');

            // Wizard Payment (Fichiers) - Nommé "file" pour cohérence
            $table->string('fichier1')->nullable();
            $table->string('fichier2')->nullable();
            $table->string('fichier3')->nullable();

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
        Schema::dropIfExists('violences');
    }
}
