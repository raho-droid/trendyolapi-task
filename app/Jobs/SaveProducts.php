<?php

namespace App\Jobs;

use App\Models\Products;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productsData;

    public function __construct($productsData)
    {
        $this->productsData = $productsData;
    }

    public function handle()
    {
        try {
            foreach ($this->productsData['content'] as $product) {
                Products::updateOrCreate(
                    ['product_id' => $product['id']], 
                    [
                        'barcode' => $product['barcode'],
                        'approved' => $product['approved'],
                        'archived' => $product['archived'],
                        'attributes' => json_encode($product['attributes']),
                        'blacklisted' => $product['blacklisted'],
                        'brand' => $product['brand'],
                        'brand_id' => $product['brandId'],
                        'category_name' => $product['categoryName'],
                        'create_date_time' => \Carbon\Carbon::createFromTimestampMs($product['createDateTime']),
                        'description' => $product['description'],
                        'dimensional_weight' => $product['dimensionalWeight'],
                        'gender' => $product['gender'] ?? null,
                        'has_active_campaign' => $product['hasActiveCampaign'],
                        'has_html_content' => $product['hasHtmlContent'],
                        'product_main_id' => $product['productMainId'],
                        'product_content_id' => $product['productContentId'],
                        'platform_listing_id' => $product['platformListingId'],
                        'product_code' => $product['productCode'],
                        'product_url' => $product['productUrl'],
                        'quantity' => $product['quantity'],
                        'list_price' => $product['listPrice'],
                        'sale_price' => $product['salePrice'],
                        'vat_rate' => $product['vatRate'],
                        'lock_reason' => $product['lockReason'] ?? null, 
                        'locked' => $product['locked'],
                        'on_sale' => $product['onSale'],
                        'pim_category_id' => $product['pimCategoryId'],
                        'returning_address_id' => $product['returningAddressId'],
                        'shipment_address_id' => $product['shipmentAddressId'],
                        'stock_code' => $product['stockCode'],
                        'title' => $product['title'],
                        'stock_unit_type' => $product['stockUnitType'],
                        'supplier_id' => $product['supplierId'],
                        'last_update_date' => \Carbon\Carbon::createFromTimestampMs($product['lastUpdateDate']),
                        'images' => json_encode($product['images']), 
                    ]
                );
            }
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'productsData' => $this->productsData,
                'error' => $e,
            ]);
            throw $e;
        }
    }
    
}
