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
            if(config('business.contacts')){
                $table->integer('contact_id')->nullable()->after('status');
            }
        });
        // Módulo General de Negocio
        if(config('business.holidays')||config('solunes.staff')){
            Schema::create('holidays', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->boolean('recurrent')->default(0);
                $table->date('initial_date')->nullable();
                $table->date('end_date')->nullable();
                $table->timestamps();
            });
        }
        if(config('business.labor_days')||config('solunes.staff')){
            Schema::create('labor_days', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->nullable();
                $table->time('initial_time')->nullable();
                $table->time('end_time')->nullable();
                $table->timestamps();
            });
        }
        if(config('business.countries')){
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order')->nullable()->default(0);
                $table->enum('continent', ['AF','AN','AS','EU','NA','OC','SA','other'])->nullable()->default('SA');
                $table->string('name')->nullable();
                $table->string('native_name')->nullable();
                $table->string('code')->nullable();
                $table->boolean('active')->nullable()->default(1);
                $table->string('phone')->nullable();
                $table->string('currency_code')->nullable();
                $table->string('languages')->nullable();
                $table->timestamps();
            });
        }
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order')->nullable()->default(0);
            if(config('business.countries')){
                $table->integer('country_id')->nullable()->default(1);
            }
            $table->string('code')->nullable();
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
            $table->integer('region_id')->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->boolean('other_city')->nullable()->default(0);
            $table->boolean('active')->nullable()->default(1);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
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
            $table->integer('parent_id')->nullable();
            $table->integer('level')->nullable();
            $table->integer('order')->nullable()->default(0);
            $table->string('name')->nullable();
            if(config('customer.different_customers_by_agency')){
                $table->string('token')->nullable();
            }
            $table->enum('type', ['central', 'store', 'office', 'storage', 'other'])->nullable()->default('store');
            $table->integer('region_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('city_other')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('map')->nullable();
            if(config('solunes.agency_shippings')&&config('business.variation_agencies')){
                if(config('sales.delivery_select_day')){
                    $table->boolean('delivery_select_day')->default(1);
                }
                if(config('sales.delivery_select_hour')){
                    $table->boolean('delivery_select_hour')->default(1);
                }
            }
            if(config('solunes.inventory')||config('business.variation_agencies')){
                $table->boolean('stockable')->default(1);
            }

            $table->timestamps();
        });
        if(config('business.agency_payment_methods')){
            Schema::create('agency_payment_method', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('agency_id')->nullable();
                $table->integer('payment_method_id')->nullable();
            });
        }
        if(config('business.agency_shippings')){
            Schema::create('agency_shipping', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('agency_id')->nullable();
                $table->integer('shipping_id')->nullable();
            });
        }
        if(config('business.companies')){
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
        }
        if(config('business.contacts')){
            Schema::create('contacts', function (Blueprint $table) {
                $table->increments('id');
                if(config('solunes.customer')){
                    $table->integer('customer_id')->nullable();
                }
                $table->string('name')->nullable();
                $table->string('firstname')->nullable();
                $table->string('lastname')->nullable();
                $table->string('email')->nullable();
                $table->enum('type', ['customer', 'supplier', 'partner', 'employee', 'other'])->nullable()->default('customer');
                if(config('business.companies')){
                    $table->integer('company_id')->nullable();
                }
                $table->string('jobtitle')->nullable();
                $table->string('phone')->nullable();
                $table->string('external_code')->nullable();
                $table->text('message')->nullable();
                $table->timestamps();
            });
        }
        if(config('business.deals')){
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
            if(config('business.companies')){
                Schema::create('deal_company', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('deal_id')->nullable();
                    $table->integer('company_id')->nullable();
                    $table->timestamps();
                });
            }
            if(config('business.contacts')){
                Schema::create('deal_contact', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('deal_id')->nullable();
                    $table->integer('contact_id')->nullable();
                    $table->timestamps();
                });
            }
        }
        if(config('business.categories')){
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('id');
                if(config('product.product_agency')){
                    $table->integer('agency_id')->nullable();
                }
                $table->integer('parent_id')->nullable();
                $table->integer('level')->nullable();
                $table->integer('order')->nullable()->default(0);
                $table->string('slug')->nullable();
                if(config('product.category_image')||config('business.category_image')){
                    $table->string('image')->nullable();
                }
                $table->timestamps();
            });
            Schema::create('category_translation', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->string('locale')->index();
                $table->string('name')->nullable();
                //if(config('product.category_description')){
                    $table->text('description')->nullable();
                //}
                $table->unique(['category_id','locale']);
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
        if(config('business.channels')){
            Schema::create('channels', function (Blueprint $table) {
                $table->increments('id');
                $table->string('slug')->nullable();
                $table->enum('type', ['public','private'])->nullable()->default('public');
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }
        if(config('business.brands')){
            Schema::create('brands', function (Blueprint $table) {
                $table->increments('id');
                if(config('product.product_agency')){
                    $table->integer('agency_id')->nullable();
                }
                $table->integer('parent_id')->nullable();
                $table->integer('level')->nullable();
                $table->integer('order')->nullable()->default(0);
                $table->string('slug')->nullable();
                if(config('product.category_image')||config('business.category_image')){
                    $table->string('image')->nullable();
                }
                $table->timestamps();
            });
        }
        Schema::create('product_bridges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_type')->nullable();
            $table->string('product_id')->nullable();
            if(config('business.product_bridge_category')){
                $table->integer('category_id')->nullable();
            }
            if(config('product.seller_user')){
                $table->integer('seller_user_id')->nullable();
            }
            if(config('product.product_agency')){
                $table->integer('agency_id')->nullable();
            }
            if(config('business.brands')){
                $table->integer('brand_id')->nullable();
            }
            $table->integer('product_bridge_parent_id')->nullable();
            $table->string('image')->nullable();
            if(config('business.product_barcode')){
                $table->string('barcode')->nullable();
            }
            if(config('business.product_sku')){
                $table->string('sku')->nullable();
            }
            $table->integer('currency_id')->unsigned();
            $table->decimal('price', 10, 2)->nullable()->default(0);
            if(config('payments.sfv_version')>1||config('payments.discounts')){
                $table->decimal('discount_price', 10, 2)->nullable();
            }
            $table->decimal('weight', 10, 2)->nullable()->default(0);
            if(config('payments.sfv_version')>1){
                $table->string('economic_sin_activity')->nullable();
                $table->string('product_sin_code')->nullable();
                $table->string('product_internal_code')->nullable();
                $table->string('product_serial_number')->nullable(); // Para linea blanca y celulares
            }
            if(config('customer.credit_wallet_points')){
                $table->boolean('points_active')->nullable()->default(0);
                $table->integer('points_price')->nullable();
            }
            if(config('solunes.inventory')){
                $table->boolean('stockable')->nullable()->default(0);
                if(config('inventory.basic_inventory')){
                    $table->integer('quantity')->nullable()->default(1);
                }
            }
            if(config('business.product_variations')){
                //$table->integer('variation_id')->nullable();
                //$table->integer('variation_option_id')->nullable();
                /*if(config('business.second_product_variations')){
                    $table->integer('variation_2_id')->nullable();
                    $table->integer('variation_option_2_id')->nullable();
                }
                if(config('business.third_product_variations')){
                    $table->integer('variation_3_id')->nullable();
                    $table->integer('variation_option_3_id')->nullable();
                }*/
            }
            $table->enum('delivery_type', ['normal','digital','subscription','reservation','ticket','credit'])->nullable()->default('normal');
            if(config('product.product_url')){
                $table->string('product_url')->nullable();
            }
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
            if(config('product.product_sold_content')){
                $table->text('sold_content')->nullable();
            }
            $table->unique(['product_bridge_id','locale']);
            $table->foreign('product_bridge_id')->references('id')->on('product_bridges')->onDelete('cascade');
        });
        if(config('business.channels')){
            Schema::create('product_bridge_channel', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_bridge_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('channel_id')->nullable();
            });
        }
        if(config('business.product_variations')){
            Schema::create('variations', function (Blueprint $table) {
                $table->increments('id');
                if(config('product.variation_agency')){
                    $table->integer('agency_id')->nullable();
                }
                $table->enum('type', ['label','choice','quantities'])->default('choice');
                $table->enum('subtype', ['normal', 'color', 'image'])->nullable()->default('normal');
                //if(config('solunes.inventory')){
                    $table->boolean('stockable')->nullable()->default(1);
                //}
                $table->boolean('advanced')->nullable()->default(0);
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
                $table->boolean('default')->nullable()->default(0);
                $table->string('color')->nullable();
                $table->string('image')->nullable();
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
            if(config('business.categories')){
                Schema::create('category_variation', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('category_id')->unsigned();
                    $table->integer('variation_id')->unsigned();
                    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                    $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
                });
            }
            Schema::create('product_bridge_variation', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_bridge_id')->nullable();
                $table->integer('variation_id')->nullable();
                $table->integer('product_id')->nullable();
                /*$table->integer('quantity')->nullable();
                $table->decimal('new_price',10,2)->nullable();
                $table->string('value')->nullable();
                $table->boolean('active')->default(1);*/
                //$table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            });
            Schema::create('product_bridge_variation_option', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_bridge_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('variation_option_id')->nullable();
                //$table->timestamps();
                //$table->foreign('product_bridge_id')->references('id')->on('product_bridges')->onDelete('cascade');
                //$table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
                //$table->foreign('variation_option_id')->references('id')->on('variation_options')->onDelete('cascade');
            });
        }
        if(config('business.pricing_rules')){
            Schema::create('pricing_rules', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->enum('type', ['automatic','coupon'])->nullable();
                $table->string('coupon_code')->nullable();
                if(config('business.channels')){
                    $table->boolean('filter_by_channel')->nullable()->default(0);
                    $table->integer('channel_id')->nullable();
                }
                $table->enum('item_type', ['general', 'category','product'])->nullable()->default('general');
                $table->integer('category_id')->nullable();
                $table->integer('product_bridge_id')->nullable();
                $table->boolean('active')->nullable()->default(1);
                $table->enum('discount_type', ['normal', 'percentage'])->nullable()->default('normal');
                //$table->integer('currency_id')->nullable();
                $table->decimal('discount_value', 10, 2)->nullable();
                $table->integer('discount_percentage')->nullable();
                $table->enum('user_usages', ['once', 'unlimited'])->nullable()->default('once');
                $table->timestamps();
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
        Schema::dropIfExists('pricing_rules');
        Schema::dropIfExists('product_bridge_variation_options');
        Schema::dropIfExists('product_bridge_variation_option');
        Schema::dropIfExists('product_bridge_channel');
        Schema::dropIfExists('product_bridge_variation');
        Schema::dropIfExists('category_variation');
        Schema::dropIfExists('product_variation');
        Schema::dropIfExists('variation_option_translation');
        Schema::dropIfExists('variation_options');
        Schema::dropIfExists('variation_translation');
        Schema::dropIfExists('variations');
        Schema::dropIfExists('product_bridge_translation');
        Schema::dropIfExists('product_bridges');
        Schema::dropIfExists('channels');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('category_variation');
        Schema::dropIfExists('category_translation');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('deal_contact');
        Schema::dropIfExists('deal_company');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('agency_shipping');
        Schema::dropIfExists('agency_payment_method');
        Schema::dropIfExists('agencies');
        Schema::dropIfExists('currency_translation');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('city_translation');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('region_translation');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('labor_days');
        Schema::dropIfExists('holidays');
    }
}
