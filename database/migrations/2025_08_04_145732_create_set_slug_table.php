<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetSlugTable extends Migration
{
    public function up()
    {
        Schema::create('set_slug', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();         // /ga4_qsha_oh など
            $table->string('label');                  // 表示名
            $table->string('type');                   // ga4 or gsc
            $table->string('handler')->nullable();    // index, show, yoy, momなど
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('active')->default(true); // 公開/非公開
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('set_slug');
    }
}
