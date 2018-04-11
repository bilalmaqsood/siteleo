<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->string('role')->default('visitor')->after('id');
            $table->string('surname')->after('name');
            $table->string('photo', 255)->after('surname');
            $table->string('phone', 255)->after('photo');
            $table->integer('sex')->length(1)->after('password');
            $table->timestamp('birthday')->nullable()->after('sex');
            $table->string('region', 255)->after('birthday');
            $table->string('city', 255)->after('region');
            $table->integer('postcode')->length(10)->after('city');
            $table->integer('worck_time_from')->length(2)->after('postcode');
            $table->integer('worck_time_to')->length(2)->after('worck_time_from');
            $table->text('worck_week_days')->after('worck_time_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'surname', 
                'sex', 
                'birthday',
                'region',
                'city',
                'postcode',
                'worck_time_from',
                'worck_time_to',
                'worck_week_days',
                'photo',
                'role',
                'phone'
            ]);
        });
    }
}
