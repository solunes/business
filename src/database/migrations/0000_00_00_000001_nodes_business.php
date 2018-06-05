<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NodesBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('agency_id')->nullable()->after('status');
            $table->integer('contact_id')->nullable()->after('status');
        });
        // Módulo General de Negocio
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            $table->boolean('active')->nullable()->default(1);
            $table->timestamps();
        });
        Schema::create('region_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('region_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->unique(['region_id','locale']);
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region_id')->unsigned();
            $table->integer('order')->nullable()->default(0);
            $table->boolean('active')->nullable()->default(1);
            $table->timestamps();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
        Schema::create('city_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('city_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->unique(['city_id','locale']);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->enum('type', ['main', 'secondary'])->nullable()->default('secondary');
            $table->decimal('main_exchange', 10, 5)->nullable()->default(1);
            $table->timestamps();
        });
        Schema::create('currency_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('currency_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->string('plural')->nullable();
            $table->unique(['currency_id','locale']);
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
        Schema::create('agencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->enum('type', ['central', 'store', 'office', 'storage', 'other'])->nullable()->default('store');
            $table->integer('region_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('map')->nullable();
            $table->timestamps();
        });
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('industry')->nullable();
            $table->string('domain')->nullable();
            $table->string('phone')->nullable();
            $table->enum('type', ['customer', 'supplier', 'partner', 'other'])->nullable()->default('customer');
            $table->string('image')->nullable();
            $table->string('external_code')->nullable();
            $table->integer('user_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', ['customer', 'supplier', 'partner', 'employee', 'other'])->nullable()->default('customer');
            $table->integer('company_id')->nullable();
            $table->string('jobtitle')->nullable();
            $table->string('phone')->nullable();
            $table->string('external_code')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
        Schema::create('deals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dealname')->nullable();
            $table->string('service')->nullable();
            $table->integer('amount')->nullable();
            $table->string('dealstage')->nullable();
            $table->string('dealtype')->nullable();
            $table->text('content')->nullable();
            $table->string('external_code')->nullable();
            $table->timestamps();
        });
        Schema::create('deal_company', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('deal_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->timestamps();
        });
        Schema::create('deal_contact', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('deal_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->timestamps();
        });
        Schema::create('product_bridges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_type')->nullable();
            $table->string('product_id')->nullable();
            $table->string('image')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->decimal('price', 10, 2)->nullable()->default(0);
            $table->decimal('weight', 10, 2)->nullable()->default(0);
            $table->timestamps();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
        Schema::create('product_bridge_translation', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_bridge_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('internal_url')->nullable();
            $table->text('content')->nullable();
            $table->unique(['product_bridge_id','locale']);
            $table->foreign('product_bridge_id')->references('id')->on('product_bridges')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Módulo General de Negocio
        Schema::dropIfExists('product_bridge_translation');
        Schema::dropIfExists('product_bridges');
        Schema::dropIfExists('deal_contact');
        Schema::dropIfExists('deal_company');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('agencies');
        Schema::dropIfExists('currency_translation');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('city_translation');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('region_translation');
        Schema::dropIfExists('regions');
    }
}
