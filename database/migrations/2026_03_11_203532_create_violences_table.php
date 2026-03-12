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

            // Informations Identification (Wizard Service)
            $table->string('code')->unique(); // Ajout d'un champ code unique pour identifier chaque cas
            $table->string('status');
            $table->string('contact');
            $table->string('occupation');
            $table->integer('age');
            $table->string('sexe'); // Changé en string car souvent 'M/F' ou via un select
            $table->string('nationalite');

            // Informations Localisation et Cas (Wizard Time)
            $table->string('residence');
            $table->date('datesurvenue');
            $table->string('lieusurvenue');
            $table->string('situation');
            $table->string('auteurs');

            // Relations
            $table->foreignId('nature_id')->constrained('natures')->onDelete('cascade');
            $table->foreignId('collecte_id')->constrained('collectes')->onDelete('cascade');
            $table->string('mode_collecte'); // Correspond au select name="mode"

            // Détails (Wizard Details)
            $table->text('description_cas')->nullable();
            $table->text('mesure_obc')->nullable();
            $table->text('risque_victime')->nullable();
            $table->text('attente_victime')->nullable();

            // Fichiers (Stockage des chemins)
            $table->string('fichie1')->nullable();
            $table->string('fichie2')->nullable();
            $table->string('fichie3')->nullable();

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
