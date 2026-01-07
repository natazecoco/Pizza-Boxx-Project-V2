<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED DATA USER (Admin & Akun Kamu)
        User::create([
            'name' => 'Pizza Boxx Admin',
            'email' => 'admin@pizzaboxx.com',
            'password' => Hash::make('12345678'), // Silakan ganti sesuai keinginan
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test_user@gmail.com',
            'password' => Hash::make('12345678'), // Silakan ganti sesuai keinginan
            'role' => 'customer',
        ]);

        // 2. SEED KATEGORI (Data asli dari SQL kamu)
        $categories = [
            ['id' => 1, 'name' => 'Pizza'],
            ['id' => 2, 'name' => 'Drink'],
            ['id' => 3, 'name' => 'Pasta'],
            ['id' => 4, 'name' => 'Side Dish'],
            ['id' => 5, 'name' => 'Extra Sauce'],
            ['id' => 6, 'name' => 'Promo'],
        ];
        foreach ($categories as $cat) { 
            Category::create($cat); 
        }

        // 3. SEED LOKASI (4 Cabang Pizza Boxx dari SQL kamu)
        $locations = [
            [
                'name' => 'Pizza Boxx, Sukahati Cibinong',
                'address' => 'Perumahan Surya Praja Permai, Jl. Anang, Blok C7, No. 11B, RT.05/RW.06, Sukahati, Cibinong',
                'latitude' => -6.48384309, 'longitude' => 106.81727130, 'delivery_radius_km' => 2
            ],
            [
                'name' => 'Pizza Boxx, Cikaret Cibinong',
                'address' => 'Jl. Raya Cikaret No. 6, Pakansari, Kecamatan Cibinong',
                'latitude' => -6.47329673, 'longitude' => 106.84323141, 'delivery_radius_km' => 2
            ],
            [
                'name' => 'Pizza Boxx, Mayor Oking Cibinong',
                'address' => 'Jl. Raya Mayor Oking Jaya Atmaja No. 158k, Cirimekar, Cibinong',
                'latitude' => -6.47648594, 'longitude' => 106.86324630, 'delivery_radius_km' => 2
            ],
            [
                'name' => 'Pizza Boxx, Bojong Gede Cibinong',
                'address' => 'Ruko Pesona Darussalam Blok A1 No.14, Waringin Jaya, Bojonggede',
                'latitude' => -6.50415345, 'longitude' => 106.79459855, 'delivery_radius_km' => 2
            ],
        ];
        foreach ($locations as $loc) { 
            Location::create($loc); 
        }

        // 4. SEED SEMUA PRODUK (39 Menu Lengkap dari SQL kamu)
        $allProducts = [
            // PIZZA
            ['category_id' => 1, 'name' => 'Sausage Mania', 'description' => 'Saus Pizza Spesial, Pepperoni Sapi, Sosis Sapi Cocktail, Sosis Sapi, Sapi Cincang, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Cheesy Basic', 'description' => 'Saus Pizza Spesial, Keju Mozzarella', 'base_price' => 25500.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Cheesy Royal', 'description' => 'Saus Keju Spesial, Keju Mozzarella', 'base_price' => 25500.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Spicy Chicken Crispy', 'description' => 'Saus Barbeque Pedas, Ayam Asap, Ayam Krispi, Jamur, Bawang Bombay, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Beef Pepperoni Pizza', 'description' => 'Saus Pizza Spesial, Pepperoni Sapi, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Hawaiian Pizza', 'description' => 'Saus Pizza Spesial, Ayam Asap, Nanas, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Meat Island', 'description' => 'Saus Thousand Island, Sapi Asap, Ayam Asap, Burger Sapi, Paprika, Jagung, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Smoked Beef Mania', 'description' => 'Saus Pizza Spesial, Sapi Asap, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Pepperoni Cheese', 'description' => 'Saus Keju Spesial, Pepperoni Sapi, Keju Mozzarella', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Beef Mushroom', 'description' => 'Saus Pizza Spesial, Pepperoni Sapi, Sapi Asap, Sosis Sapi, Sapi Cincang, Jamur, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Black Pepper Pizza', 'description' => 'Saus Lada Hitam, Sapi Asap, Ayam Asap, Burger Sapi, Paprika, Bawang Bombay, Sapi Cincang, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Meat Mania', 'description' => 'Saus Pizza Spesial, Pepperoni Sapi, Ayam Asap, Burger Sapi, Sosis Sapi, Sapi Cincang, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Pizza Hot Cheese', 'description' => 'Saus Barbeque Pedas, Pepperoni Sapi, Sosis Sapi Cocktail, Bawang Bombay, Sapi Cincang, Keju Mozzarella, Saus Keju', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Special Mania', 'description' => 'Saus Pizza Spesial, Pepperoni Sapi, Ayam Asap, Burger Sapi, Paprika, Bawang Bombay, Jamur, Nanas, Sapi Cincang, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Spicy Bbq', 'description' => 'Saus Barbeque Pedas, Pepperoni Sapi, Ayam Asap, Sapi Cincang, Jamur, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 1, 'name' => 'Spicy Tuna', 'description' => 'Saus Thousand Island, Tuna Pedas, Paprika, Bawang Bombay, Jagung, Keju Mozzarella', 'base_price' => 28000.00, 'is_available' => true],
            // DRINK
            ['category_id' => 2, 'name' => 'Ice Lemon Tea', 'description' => 'Teh dengan Rasa Lemon', 'base_price' => 10000.00, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Choco Latte', 'description' => 'Coklat, Susu Sapi Segar, Gula Masak', 'base_price' => 19000.00, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Green Tea Latte', 'description' => 'Teh Hijau, Susu Sapi Segar, Gula Masak', 'base_price' => 19000.00, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Green Tea Coffee', 'description' => 'Teh Hijau, Susu Segar, Kopi', 'base_price' => 20000.00, 'is_available' => true],
            ['category_id' => 2, 'name' => 'Mocha Choco', 'description' => 'Coklat, Susu Sapi Segar, Gula, Kopi', 'base_price' => 20000.00, 'is_available' => true],
            // PASTA
            ['category_id' => 3, 'name' => 'Fettucine Beef', 'description' => 'Pasta Fettucine, Krim Lezat, Bawang Bombay, Jamur, Sapi Asap', 'base_price' => 33000.00, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Fettucine Black Pep Beef', 'description' => 'Pasta Fettucine, Saus Lada Hitam, Krim Lezat, Bawang Bombay, Paprika, Sapi Asap, Sapi Cincang', 'base_price' => 33000.00, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Fettucine Creamy Bolognese', 'description' => 'Pasta Fettucine, Saus Bolognese, Krim Lezat, Bawang Bombay, Sapi Asap, Sapi Cincang', 'base_price' => 33000.00, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Fettucine Cheese', 'description' => 'Pasta Fettucine, Krim Lezat, Saus Keju Spesial, Pepperoni Sapi, Keju Cheddar', 'base_price' => 33000.00, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Fettucine Spicy Bbq', 'description' => 'Pasta Fettucine, Ayam Asap, Sapi Asap, Bawang Bombay, Jamur, Saus Bbq Pedas', 'base_price' => 33000.00, 'is_available' => true],
            ['category_id' => 3, 'name' => 'Beef Lasagna', 'description' => 'Kulit Lasagna, Saus Daging, Krim Lezat, Keju Mozzarella', 'base_price' => 43000.00, 'is_available' => false],
            // SIDE DISH
            ['category_id' => 4, 'name' => 'Mash Potato Bolognese', 'description' => 'Kentang Halus, Saus Daging, Saus Putih, Mozzarella, Tepung Roti', 'base_price' => 35500.00, 'is_available' => false],
            ['category_id' => 4, 'name' => 'Beef Bockwurst Single', 'description' => '1 pc Sosis Jumbo Premium dengan 3 Saus', 'base_price' => 14000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Potato Wedges', 'description' => 'Kentang Impor yang dipanggang garing di luar dan lembut di dalam', 'base_price' => 19000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Wings Double', 'description' => '3 pcs Sayap Ayam Home Made, Kentang Panggang Impor', 'base_price' => 26500.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Beef Bockwurst Double', 'description' => '2 pcs Sosis Jumbo Premium dengan 3 Saus', 'base_price' => 27000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Beef Cocktail 10 pcs', 'description' => 'Sosis Sapi kualitas premium dengan tambahan Saus Barbeque', 'base_price' => 28000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Chicken Wings 6 pcs', 'description' => 'Sayap Ayam home made khas Pizza Boxx', 'base_price' => 34000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Beef Bockwurst Triple', 'description' => '3 pcs Sosis Jumbo Premium dengan 3 Saus', 'base_price' => 39000.00, 'is_available' => true],
            ['category_id' => 4, 'name' => 'Chicken Balls', 'description' => 'Daging Ayam Olahan home made khas Pizza Boxx', 'base_price' => 24000.00, 'is_available' => false],
            // EXTRA SAUCE
            ['category_id' => 5, 'name' => 'Bbq Sauce', 'description' => 'Cocolan Saus Barbeque', 'base_price' => 5000.00, 'is_available' => true],
            ['category_id' => 5, 'name' => 'Cheese Sauce', 'description' => 'Cocolan Saus Keju', 'base_price' => 5000.00, 'is_available' => true],
            ['category_id' => 5, 'name' => 'Hot Bbq Sauce', 'description' => 'Cocolan Saus Barbeque Pedas', 'base_price' => 5000.00, 'is_available' => true],
        ];

        foreach ($allProducts as $product) {
            Product::create($product);
        }

        // 5. SEED PROMO (Agar Testing Berjalan Lancar)
        Promo::create([
            'code' => 'DISKON10',
            'name' => 'DISKON 10%',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 30000,
            'is_active' => true,
        ]);
        
        // 6. SEED SEMUA PRODUCT OPTIONS (Ukuran & Pinggiran Lengkap)
        $productOptions = [
            // --- UKURAN (Harga tambahan tergantung Jenis Pizza) ---
            ['product_id' => 5, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 5, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 5, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 11, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 11, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 11, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 10, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 10, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 10, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 2, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 2, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 29500.00],
            ['product_id' => 2, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 57500.00],
            ['product_id' => 3, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 3, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 29500.00],
            ['product_id' => 3, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 57500.00],
            ['product_id' => 6, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 6, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 6, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 7, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 7, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 7, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 12, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 12, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 12, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 9, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 9, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 9, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 13, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 13, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 13, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 1, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 1, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 1, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 8, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 8, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 8, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 14, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 14, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 14, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 15, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 15, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 15, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],
            ['product_id' => 4, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 4, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 31000.00],
            ['product_id' => 4, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 60000.00],
            ['product_id' => 16, 'type' => 'Ukuran', 'name' => 'Personal', 'price_modifier' => 0.00],
            ['product_id' => 16, 'type' => 'Ukuran', 'name' => 'Reguler', 'price_modifier' => 32000.00],
            ['product_id' => 16, 'type' => 'Ukuran', 'name' => 'Large', 'price_modifier' => 62000.00],

            // --- PINGGIRAN (Harga tambahan tergantung Ukuran Pizza) ---
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Keju Mozzarella (P)', 'price_modifier' => 12000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Nugget Ayam dan Pepperoni Sapi (P)', 'price_modifier' => 11000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Sosis Sapi Cocktail (P)', 'price_modifier' => 11000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Keju Mozzarella (R)', 'price_modifier' => 18000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Nugget Ayam dan Pepperoni Sapi (R)', 'price_modifier' => 16500.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Sosis Sapi Cocktail (R)', 'price_modifier' => 16500.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Keju Mozzarella (L)', 'price_modifier' => 24000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Nugget Ayam dan Pepperoni Sapi (L)', 'price_modifier' => 22000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Sosis Sapi Cocktail (L)', 'price_modifier' => 22000.00],
            ['product_id' => 10, 'type' => 'Pinggiran', 'name' => 'Original', 'price_modifier' => 0.00],
        ];

        foreach ($productOptions as $option) {
            \App\Models\ProductOption::create($option);
        }
    }
}