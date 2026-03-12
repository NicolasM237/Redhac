<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activites', function (Blueprint $table) {
            $table->id();

            // Utilisateur qui fait l'action
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Type d'action : création, modification, suppression, consultation...
            $table->string('action_type');

            // Table ou entité concernée
            $table->string('table_name')->nullable();

            // ID de l'entité concernée
            $table->unsignedBigInteger('entity_id')->nullable();

            // Description détaillée (optionnel)
            $table->text('description')->nullable();

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
        Schema::dropIfExists('activites');
    }
}
