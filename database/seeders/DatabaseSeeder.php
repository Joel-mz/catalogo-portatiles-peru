<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Administrator with full access']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'Customer'],
            ['description' => 'Regular customer']
        );

        User::firstOrCreate(
            ['email' => 'admin@moyotech.com'],
            [
                'name' => 'MOYO TECH Admin',
                'password' => bcrypt('password'),
                'role_id' => $adminRole->id,
            ]
        );

        $catLaptops = Category::firstOrCreate(['slug' => 'laptops'], ['name' => 'Laptops']);
        $catAcc = Category::firstOrCreate(['slug' => 'accesorios'], ['name' => 'Accesorios']);

        $brandApple = Brand::firstOrCreate(['slug' => 'apple'], ['name' => 'Apple']);
        $brandDell = Brand::firstOrCreate(['slug' => 'dell'], ['name' => 'Dell']);
        $brandAsus = Brand::firstOrCreate(['slug' => 'asus'], ['name' => 'ASUS']);
        $brandLenovo = Brand::firstOrCreate(['slug' => 'lenovo'], ['name' => 'Lenovo']);
        $brandHP = Brand::firstOrCreate(['slug' => 'hp'], ['name' => 'HP']);

        Product::firstOrCreate(
            ['sku' => 'SKU-APP-001'],
            [
                'code' => 'APP-MBP-16',
                'name' => 'MacBook Pro 16" M3 Max',
                'slug' => 'macbook-pro-16-m3-max',
                'category_id' => $catLaptops->id,
                'brand_id' => $brandApple->id,
                'description' => 'La laptop más avanzada de Apple para profesionales.',
                'technical_specs' => ['Processor' => 'M3 Max', 'RAM' => '36GB', 'Storage' => '1TB SSD'],
                'price' => 3499.00,
                'offer_price' => 3299.00,
                'warranty' => '1 Año',
                'stock' => 10,
                'is_featured' => true,
            ]
        );

        Product::firstOrCreate(
            ['sku' => 'SKU-ASU-001'],
            [
                'code' => 'ASU-ROG-15',
                'name' => 'ASUS ROG Zephyrus G15',
                'slug' => 'asus-rog-zephyrus-g15',
                'category_id' => $catLaptops->id,
                'brand_id' => $brandAsus->id,
                'description' => 'Rendimiento extremo para gaming y creación.',
                'technical_specs' => ['Processor' => 'Ryzen 9', 'RAM' => '16GB', 'Storage' => '1TB SSD', 'GPU' => 'RTX 3080'],
                'price' => 1999.00,
                'warranty' => '1 Año',
                'stock' => 5,
                'is_featured' => true,
            ]
        );
        
        // Settings
        \App\Models\Setting::firstOrCreate(['key' => 'store_name'], ['value' => 'PORTÁTILES PERÚ / MOYO TECH', 'type' => 'string']);
        \App\Models\Setting::firstOrCreate(['key' => 'whatsapp_number'], ['value' => '+51999999999', 'type' => 'string']);
    }
}
