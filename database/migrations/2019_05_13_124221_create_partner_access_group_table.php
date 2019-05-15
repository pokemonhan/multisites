<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePartnerAccessGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_access_group', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('group_name', 15);
			$table->text('role', 65535)->nullable();
			$table->integer('status')->unsigned()->nullable()->default(1);
			$table->timestamps();
			$table->integer('platform_id')->unsigned()->nullable()->default(1)->index('fk_partner_access_platform_id_idx');
			$table->unique(['group_name','platform_id'], 'group_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('partner_access_group');
	}

}