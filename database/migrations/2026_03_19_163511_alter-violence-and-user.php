<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterViolenceAndUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('violences') && !Schema::hasColumn('violences', 'coordinates')){
            Schema::table('violences', function(Blueprint $b){
                $b->string('coordinates')->nullable();
                $b->boolean('can_modify')->default(false);
            });
        }

        if(Schema::hasTable('users') && !Schema::hasColumn('users', 'sexe')){
            Schema::table('users', function(Blueprint $b){
                $b->string('sexe')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
