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
        if(config('business.countries')){
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order')->nullable()->default(0);
                $table->string('name')->nullable()->default(1);
                $table->boolean('active')->nullable()->default(1);
                $table->timestamps();
            });
        }
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            if(config('business.countries')){
                $table->integer('country_id')->nullable()->default(1);
            }
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
            $table->integer('parent_agency_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('map')->nullable();
            if(config('solunes.inventory')){
                $table->boolean('stockable')->default(1);
            }
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
            if(config('payments.sfv_version')>1||config('payments.discounts')){
                $table->decimal('discount_price', 10, 2)->nullable();
            }
            if(config('payments.sfv_version')>1){
                $table->string('economic_sin_activity')->nullable();
                $table->string('product_sin_code')->nullable();
                $table->string('product_internal_code')->nullable();
                $table->string('product_serial_number')->nullable(); // Para linea blanca y celulares
            }
            if(config('solunes.inventory')){
                $table->boolean('stockable')->nullable()->default(0);
            }
            if(config('business.product_variations')){
                $table->integer('variation_id')->nullable();
                $table->integer('variation_option_id')->nullable();
                if(config('business.second_product_variations')){
                    $table->integer('variation_2_id')->nullable();
                    $table->integer('variation_option_2_id')->nullable();
                }
                if(config('business.third_product_variations')){
                    $table->integer('variation_3_id')->nullable();
                    $table->integer('variation_option_3_id')->nullable();
                }
            }
            $table->enum('delivery_type', ['normal','digital','link'])->default('normal');
            $table->boolean('active')->default(1);
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
        if(config('business.product_variations')){
            Schema::create('variations', function (Blueprint $table) {
                $table->increments('id');
                $table->enum('type', ['choice','quantities'])->default('choice');
                $table->enum('subtype', ['normal', 'color', 'image'])->nullable()->default('normal');
                if(config('solunes.inventory')){
                    $table->boolean('stockable')->nullable()->default(1);
                }
                $table->integer('max_choices')->nullable()->default(0);
                $table->boolean('optional')->nullable()->default(0);
                $table->timestamps();
            });
            Schema::create('variation_translation', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('variation_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->string('label')->nullable();
                $table->unique(['variation_id','locale']);
                $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            });
            Schema::create('variation_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('parent_id')->nullable();
                $table->decimal('extra_price', 10, 2)->nullable()->default(0);
                $table->integer('max_quantity')->nullable()->default(0);
                $table->timestamps();
            });
            Schema::create('variation_option_translation', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('variation_option_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->unique(['variation_option_id','locale']);
                $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('cascade');
            });
            Schema::create('product_bridge_variation_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_bridge_id')->unsigned();
                $table->integer('variation_id')->unsigned();
                $table->integer('variation_option_id')->unsigned();
                $table->timestamps();
                $table->foreign('product_bridge_id')->references('id')->on('product_bridges')->onDelete('cascade');
                $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
                $table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('cascade');
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
        // Módulo General de Negocio
        Schema::dropIfExists('product_bridge_variation_options');
        Schema::dropIfExists('product_bridge_variation');
        Schema::dropIfExists('variation_option_translation');
        Schema::dropIfExists('variation_options');
        Schema::dropIfExists('variation_translation');
        Schema::dropIfExists('variations');
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
        Schema::dropIfExists('countries');
    }
}
