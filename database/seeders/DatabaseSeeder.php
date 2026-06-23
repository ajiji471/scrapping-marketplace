<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\MarketplaceData;
use App\Models\PriceHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. User
            $this->seedUsers();

            // 2. Products
            $this->seedProducts();

            // 3. Marketplace Data
            $this->seedMarketplaceData();

            // 4. Price History
            $this->seedPriceHistory();
        });
    }

    private function seedUsers(): void
    {
        User::factory()->create([
            'name' => 'ajiji',
            'email' => 'ajiji@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@spk.test',
            'password' => bcrypt('admin123'),
        ]);
    }

    private function seedProducts(): void
    {
        $products = [
            // === LAPTOP ===
            [
                'name' => 'MacBook Air M3 13"',
                'category' => 'laptop',
                'subcategory' => 'ultrabook',
                'price_china_cny' => 7200.00,
                'price_indonesia_idr' => 22500000,
                'shipping_cost_idr' => 350000,
                'tax_estimate_idr' => 1800000,
                'weight_kg' => 1.24,
                'dimensions' => ['w' => 30.41, 'h' => 21.50, 'd' => 1.13],
                'source_url_china' => 'https://apple.com.cn/macbook-air',
            ],
            [
                'name' => 'ASUS ROG Zephyrus G14',
                'category' => 'laptop',
                'subcategory' => 'gaming',
                'price_china_cny' => 8500.00,
                'price_indonesia_idr' => 28999000,
                'shipping_cost_idr' => 450000,
                'tax_estimate_idr' => 2300000,
                'weight_kg' => 1.72,
                'dimensions' => ['w' => 31.1, 'h' => 22.7, 'd' => 1.85],
                'source_url_china' => 'https://asus.com.cn/rog-g14',
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'category' => 'laptop',
                'subcategory' => 'business',
                'price_china_cny' => 6800.00,
                'price_indonesia_idr' => 19800000,
                'shipping_cost_idr' => 320000,
                'tax_estimate_idr' => 1600000,
                'weight_kg' => 1.12,
                'dimensions' => ['w' => 31.5, 'h' => 22.2, 'd' => 1.53],
                'source_url_china' => 'https://lenovo.com.cn/thinkpad-x1',
            ],
            [
                'name' => 'HP Pavilion 14',
                'category' => 'laptop',
                'subcategory' => 'consumer',
                'price_china_cny' => 3200.00,
                'price_indonesia_idr' => 8999000,
                'shipping_cost_idr' => 280000,
                'tax_estimate_idr' => 800000,
                'weight_kg' => 1.41,
                'dimensions' => ['w' => 32.4, 'h' => 22.6, 'd' => 1.79],
                'source_url_china' => 'https://hp.com.cn/pavilion-14',
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'category' => 'laptop',
                'subcategory' => 'ultrabook',
                'price_china_cny' => 7800.00,
                'price_indonesia_idr' => 24999000,
                'shipping_cost_idr' => 380000,
                'tax_estimate_idr' => 1950000,
                'weight_kg' => 1.26,
                'dimensions' => ['w' => 29.5, 'h' => 19.9, 'd' => 1.55],
                'source_url_china' => 'https://dell.com.cn/xps-13',
            ],

            // === SMARTPHONE ===
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'category' => 'smartphone',
                'subcategory' => 'flagship',
                'price_china_cny' => 8500.00,
                'price_indonesia_idr' => 21999000,
                'shipping_cost_idr' => 150000,
                'tax_estimate_idr' => 2200000,
                'weight_kg' => 0.221,
                'dimensions' => ['w' => 7.68, 'h' => 15.98, 'd' => 0.83],
                'source_url_china' => 'https://apple.com.cn/iphone-15-pro',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'smartphone',
                'subcategory' => 'flagship',
                'price_china_cny' => 7200.00,
                'price_indonesia_idr' => 18999000,
                'shipping_cost_idr' => 150000,
                'tax_estimate_idr' => 1900000,
                'weight_kg' => 0.233,
                'dimensions' => ['w' => 7.93, 'h' => 16.28, 'd' => 0.86],
                'source_url_china' => 'https://samsung.com.cn/galaxy-s24',
            ],
            [
                'name' => 'Xiaomi 14 Pro',
                'category' => 'smartphone',
                'subcategory' => 'flagship',
                'price_china_cny' => 4200.00,
                'price_indonesia_idr' => 10999000,
                'shipping_cost_idr' => 120000,
                'tax_estimate_idr' => 1100000,
                'weight_kg' => 0.223,
                'dimensions' => ['w' => 7.54, 'h' => 16.13, 'd' => 0.83],
                'source_url_china' => 'https://mi.com/xiaomi-14',
            ],
            [
                'name' => 'OPPO Find X7 Ultra',
                'category' => 'smartphone',
                'subcategory' => 'flagship',
                'price_china_cny' => 4800.00,
                'price_indonesia_idr' => 12999000,
                'shipping_cost_idr' => 120000,
                'tax_estimate_idr' => 1250000,
                'weight_kg' => 0.221,
                'dimensions' => ['w' => 7.65, 'h' => 16.42, 'd' => 0.91],
                'source_url_china' => 'https://oppo.com/find-x7',
            ],
            [
                'name' => 'realme GT 5 Pro',
                'category' => 'smartphone',
                'subcategory' => 'mid-range',
                'price_china_cny' => 2800.00,
                'price_indonesia_idr' => 7499000,
                'shipping_cost_idr' => 100000,
                'tax_estimate_idr' => 750000,
                'weight_kg' => 0.218,
                'dimensions' => ['w' => 7.56, 'h' => 16.16, 'd' => 0.89],
                'source_url_china' => 'https://realme.com/gt5-pro',
            ],

            // === TABLET ===
            [
                'name' => 'iPad Pro 12.9" M2',
                'category' => 'tablet',
                'subcategory' => 'pro',
                'price_china_cny' => 6500.00,
                'price_indonesia_idr' => 17999000,
                'shipping_cost_idr' => 200000,
                'tax_estimate_idr' => 1650000,
                'weight_kg' => 0.682,
                'dimensions' => ['w' => 28.06, 'h' => 21.49, 'd' => 0.64],
                'source_url_china' => 'https://apple.com.cn/ipad-pro',
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra',
                'category' => 'tablet',
                'subcategory' => 'pro',
                'price_china_cny' => 5800.00,
                'price_indonesia_idr' => 15999000,
                'shipping_cost_idr' => 200000,
                'tax_estimate_idr' => 1500000,
                'weight_kg' => 0.732,
                'dimensions' => ['w' => 32.67, 'h' => 20.87, 'd' => 0.56],
                'source_url_china' => 'https://samsung.com.cn/tab-s9',
            ],
            [
                'name' => 'Xiaomi Pad 6 Pro',
                'category' => 'tablet',
                'subcategory' => 'consumer',
                'price_china_cny' => 2200.00,
                'price_indonesia_idr' => 6299000,
                'shipping_cost_idr' => 150000,
                'tax_estimate_idr' => 600000,
                'weight_kg' => 0.490,
                'dimensions' => ['w' => 25.37, 'h' => 16.58, 'd' => 0.67],
                'source_url_china' => 'https://mi.com/pad-6',
            ],

            // === SMARTWATCH ===
            [
                'name' => 'Apple Watch Ultra 2',
                'category' => 'smartwatch',
                'subcategory' => 'premium',
                'price_china_cny' => 5200.00,
                'price_indonesia_idr' => 13999000,
                'shipping_cost_idr' => 80000,
                'tax_estimate_idr' => 1300000,
                'weight_kg' => 0.061,
                'dimensions' => ['w' => 4.9, 'h' => 4.9, 'd' => 1.46],
                'source_url_china' => 'https://apple.com.cn/watch-ultra',
            ],
            [
                'name' => 'Samsung Galaxy Watch 6 Classic',
                'category' => 'smartwatch',
                'subcategory' => 'premium',
                'price_china_cny' => 2200.00,
                'price_indonesia_idr' => 5999000,
                'shipping_cost_idr' => 60000,
                'tax_estimate_idr' => 550000,
                'weight_kg' => 0.059,
                'dimensions' => ['w' => 4.65, 'h' => 4.65, 'd' => 1.07],
                'source_url_china' => 'https://samsung.com.cn/watch-6',
            ],
            [
                'name' => 'Huawei Watch GT 4',
                'category' => 'smartwatch',
                'subcategory' => 'mid-range',
                'price_china_cny' => 1200.00,
                'price_indonesia_idr' => 3499000,
                'shipping_cost_idr' => 50000,
                'tax_estimate_idr' => 300000,
                'weight_kg' => 0.048,
                'dimensions' => ['w' => 4.61, 'h' => 4.61, 'd' => 1.09],
                'source_url_china' => 'https://huawei.com/watch-gt4',
            ],

            // === TWS EARBUDS ===
            [
                'name' => 'AirPods Pro 2 USB-C',
                'category' => 'tws',
                'subcategory' => 'premium',
                'price_china_cny' => 1550.00,
                'price_indonesia_idr' => 4299000,
                'shipping_cost_idr' => 40000,
                'tax_estimate_idr' => 400000,
                'weight_kg' => 0.050,
                'dimensions' => ['w' => 6.06, 'h' => 4.52, 'd' => 2.16],
                'source_url_china' => 'https://apple.com.cn/airpods-pro',
            ],
            [
                'name' => 'Samsung Galaxy Buds 3 Pro',
                'category' => 'tws',
                'subcategory' => 'premium',
                'price_china_cny' => 1100.00,
                'price_indonesia_idr' => 2999000,
                'shipping_cost_idr' => 35000,
                'tax_estimate_idr' => 280000,
                'weight_kg' => 0.046,
                'dimensions' => ['w' => 5.03, 'h' => 4.83, 'd' => 2.69],
                'source_url_china' => 'https://samsung.com.cn/buds-3',
            ],
            [
                'name' => 'Xiaomi Buds 4 Pro',
                'category' => 'tws',
                'subcategory' => 'mid-range',
                'price_china_cny' => 650.00,
                'price_indonesia_idr' => 1799000,
                'shipping_cost_idr' => 30000,
                'tax_estimate_idr' => 165000,
                'weight_kg' => 0.042,
                'dimensions' => ['w' => 5.0, 'h' => 4.5, 'd' => 2.5],
                'source_url_china' => 'https://mi.com/buds-4',
            ],
            [
                'name' => 'Anker Soundcore Liberty 4 NC',
                'category' => 'tws',
                'subcategory' => 'budget',
                'price_china_cny' => 380.00,
                'price_indonesia_idr' => 999000,
                'shipping_cost_idr' => 25000,
                'tax_estimate_idr' => 95000,
                'weight_kg' => 0.038,
                'dimensions' => ['w' => 5.5, 'h' => 5.0, 'd' => 2.8],
                'source_url_china' => 'https://anker.com/liberty-4',
            ],

            // === MONITOR ===
            [
                'name' => 'LG UltraGear 27" 4K 144Hz',
                'category' => 'monitor',
                'subcategory' => 'gaming',
                'price_china_cny' => 2800.00,
                'price_indonesia_idr' => 7999000,
                'shipping_cost_idr' => 500000,
                'tax_estimate_idr' => 750000,
                'weight_kg' => 6.3,
                'dimensions' => ['w' => 61.4, 'h' => 57.3, 'd' => 27.9],
                'source_url_china' => 'https://lg.com.cn/ultragear-27',
            ],
            [
                'name' => 'Xiaomi Monitor 34" Curved',
                'category' => 'monitor',
                'subcategory' => 'ultrawide',
                'price_china_cny' => 1600.00,
                'price_indonesia_idr' => 4599000,
                'shipping_cost_idr' => 450000,
                'tax_estimate_idr' => 450000,
                'weight_kg' => 7.6,
                'dimensions' => ['w' => 80.8, 'h' => 52.2, 'd' => 24.2],
                'source_url_china' => 'https://mi.com/monitor-34',
            ],

            // === KEYBOARD & MOUSE ===
            [
                'name' => 'Keychron Q1 Pro QMK',
                'category' => 'keyboard',
                'subcategory' => 'mechanical',
                'price_china_cny' => 980.00,
                'price_indonesia_idr' => 2799000,
                'shipping_cost_idr' => 120000,
                'tax_estimate_idr' => 280000,
                'weight_kg' => 1.78,
                'dimensions' => ['w' => 32.8, 'h' => 14.5, 'd' => 3.5],
                'source_url_china' => 'https://keychron.com/q1-pro',
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'category' => 'mouse',
                'subcategory' => 'wireless',
                'price_china_cny' => 520.00,
                'price_indonesia_idr' => 1499000,
                'shipping_cost_idr' => 60000,
                'tax_estimate_idr' => 150000,
                'weight_kg' => 0.141,
                'dimensions' => ['w' => 8.4, 'h' => 12.4, 'd' => 5.1],
                'source_url_china' => 'https://logitech.com.cn/mx-master-3s',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }

    private function seedMarketplaceData(): void
    {
        $marketplaces = ['shopee', 'tokopedia', 'tiktok', 'lazada'];
        
        Product::all()->each(function (Product $product) use ($marketplaces) {
            // Random 2-4 marketplace per product
            $selectedMarketplaces = collect($marketplaces)->random(rand(2, 4));

            foreach ($selectedMarketplaces as $marketplace) {
                $basePrice = $product->price_indonesia_idr;
                $discountPercent = rand(0, 25);
                $currentPrice = $basePrice * (1 - $discountPercent / 100);
                
                // Variasi harga antar marketplace ±10%
                $priceVariation = (rand(-10, 10) / 100);
                $currentPrice = $currentPrice * (1 + $priceVariation);

                MarketplaceData::create([
                    'product_id' => $product->id,
                    'marketplace' => $marketplace,
                    'current_price' => round($currentPrice, -3), // bulatkan ke ribuan
                    'original_price' => $basePrice,
                    'sold_count' => rand(50, 15000),
                    'rating' => round(rand(30, 50) / 10, 1),
                    'review_count' => rand(10, 5000),
                    'wishlist_count' => rand(100, 20000),
                    'search_volume_estimate' => rand(500, 50000),
                    'scraped_at' => now()->subDays(rand(0, 7)),
                ]);
            }
        });
    }

    private function seedPriceHistory(): void
    {
        Product::all()->each(function (Product $product) {
            // China price history (6 bulan)
            $chinaBase = $product->price_china_cny;
            for ($i = 5; $i >= 0; $i--) {
                $fluctuation = (rand(-8, 12) / 100); // -8% sampai +12%
                $price = $chinaBase * (1 + $fluctuation * (5 - $i) / 5);
                
                PriceHistory::create([
                    'product_id' => $product->id,
                    'source' => 'china',
                    'price' => round($price, 2),
                    'currency' => 'CNY',
                    'note' => $i === 0 ? null : 'monthly_update',
                    'recorded_at' => now()->subMonths($i)->startOfMonth(),
                ]);
            }

            // Indonesia price history (6 bulan)
            $indoBase = $product->price_indonesia_idr;
            for ($i = 5; $i >= 0; $i--) {
                $fluctuation = (rand(-5, 8) / 100);
                $price = $indoBase * (1 + $fluctuation * (5 - $i) / 5);
                
                PriceHistory::create([
                    'product_id' => $product->id,
                    'source' => 'indonesia',
                    'price' => round($price, -3),
                    'currency' => 'IDR',
                    'note' => $i === 0 ? null : 'monthly_update',
                    'recorded_at' => now()->subMonths($i)->startOfMonth(),
                ]);
            }
        });
    }
}