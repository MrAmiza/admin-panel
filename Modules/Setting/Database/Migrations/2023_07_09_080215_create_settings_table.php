<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Setting\Fields\SettingFields;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string(SettingFields::KEY)->unique();
            $table->string(SettingFields::TITLE)->nullable();
            $table->text(SettingFields::DESCRIPTION)->nullable();
            $table->text(SettingFields::VALUE)->nullable();
            $table->boolean(SettingFields::STATUS)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
