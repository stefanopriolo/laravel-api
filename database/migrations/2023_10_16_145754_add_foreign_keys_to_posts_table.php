<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // user_id

            // crea una colonna di tipo unsignedBigInteger
            $table->unsignedBigInteger('user_id')->nullable()->after("slug");

            // rendo la colonna user_id una foreign key
            $table->foreign('user_id')
                // che fa riferimento alla colonna id della tabella users
                ->references('id')
                ->on("users")
                // se l'utente viene cancellato, cancella anche i suoi post
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // rimuovo la foreign key tramite il nome dell'indice assegnato automaticamente
            $table->dropForeign('posts_user_id_foreign');

            // rimuovo la colonna user_id
            $table->dropColumn('user_id');
        });
    }
};
