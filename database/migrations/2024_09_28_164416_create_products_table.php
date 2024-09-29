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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique(); 
            $table->string('barcode')->nullable();
            $table->string('title')->nullable();
            $table->boolean('approved')->default(false);
            $table->boolean('archived')->default(false);
            $table->json('attributes')->nullable();
            $table->boolean('blacklisted')->default(false);
            $table->string('brand')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable(); 
            $table->string('category_name')->nullable();
            $table->timestamp('create_date_time')->nullable(); 
            $table->text('description')->nullable();
            $table->float('dimensional_weight')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('has_active_campaign')->default(false);
            $table->boolean('has_html_content')->default(false);
            $table->string('product_main_id')->nullable();
            $table->string('product_content_id')->nullable();
            $table->string('platform_listing_id')->nullable(); 
            $table->string('product_code')->nullable();
            $table->text('product_url')->nullable();
            $table->integer('quantity')->nullable();
            $table->float('list_price')->nullable();
            $table->float('sale_price')->nullable();
            $table->float('vat_rate')->nullable();
            $table->string('lock_reason')->nullable();
            $table->boolean('locked')->default(false);
            $table->boolean('on_sale')->default(false);
            $table->unsignedBigInteger('pim_category_id')->nullable();
            $table->unsignedBigInteger('returning_address_id')->nullable();
            $table->unsignedBigInteger('shipment_address_id')->nullable();
            $table->string('stock_code')->nullable();
            $table->string('stock_unit_type')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->timestamp('last_update_date')->nullable();
            $table->json('images')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
