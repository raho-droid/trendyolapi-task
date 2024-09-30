<?php

namespace App\Http\Controllers;

use App\Jobs\SaveProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Products;
class ProductController extends Controller
{
    public function saveProducts()
    {
        $supplierId = env('SUPPLIER_ID');
        $apiKey = env('API_KEY');
        $apiSecret = env('API_SECRET');
        $size = 100; 
        $url = "https://api.trendyol.com/sapigw/suppliers/{$supplierId}/products?approved=true&page=0&size={$size}"; 
        
        try {
            $response = Http::withBasicAuth($apiKey, $apiSecret)
              ->get($url);
            $data = $response->json();

            if (!isset($data['totalPages'])) {
                Log::error('Toplam sayfa bilgisi alınamadı');
                return response()->json(['error' => 'Toplam sayfa bilgisi alınamadı'], 500);
            }

            $totalPages = $data['totalPages'];
            for ($page = 0; $page < $totalPages; $page++) {
                $urlPage = "https://api.trendyol.com/sapigw/suppliers/{$supplierId}/products?approved=true&page={$page}&size={$size}";
                
                try {
                    $response = Http::withBasicAuth($apiKey, $apiSecret)
                        ->get($urlPage);

                    $productsData = $response->json();

                    if (isset($productsData['content'])) {
                        SaveProducts::dispatch($productsData);
                    } else {
                        Log::warning("Ürün verisi bulunamadı: Page {$page}");
                    }

                } catch (\Exception $e) {
                    Log::error("API isteği başarısız oldu: Page {$page}, Hata: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error("API'ye bağlanılamadı: " . $e->getMessage());
            return response()->json(['error' => 'Veri çekilemedi. Lütfen tekrar deneyin.'], 500);
        }
    }

    public function getProducts(Request $request)
    {
        $search = $request->input('search');
        $query = Products::query();
    
        // Arama işlemi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('brand', 'LIKE', '%' . $search . '%')
                  ->orWhere('gender', 'LIKE', '%' . $search . '%');
            });
        }
    
        // Sayfalama
        $products = $query->paginate(10);
        
        // Yanıtı döndür
        return response()->json($products);
    }
    
}
