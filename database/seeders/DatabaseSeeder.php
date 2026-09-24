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
        $adminRole = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator with full access'
        ]);

        $userRole = Role::create([
            'name' => 'Customer',
            'description' => 'Regular customer'
        ]);

        User::create([
            'name' => 'MOYO TECH Admin',
            'email' => 'admin@moyotech.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        $catLaptops = Category::create(['name' => 'Laptops', 'slug' => 'laptops']);
        $catAcc = Category::create(['name' => 'Accesorios', 'slug' => 'accesorios']);

        $brandApple = Brand::create(['name' => 'Apple', 'slug' => 'apple']);
        $brandDell = Brand::create(['name' => 'Dell', 'slug' => 'dell']);
        $brandAsus = Brand::create(['name' => 'ASUS', 'slug' => 'asus']);
        $brandLenovo = Brand::create(['name' => 'Lenovo', 'slug' => 'lenovo']);
        $brandHP = Brand::create(['name' => 'HP', 'slug' => 'hp']);

        Product::create([
            'code' => 'APP-MBP-16',
            'sku' => 'SKU-APP-001',
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
        ]);

        Product::create([
            'code' => 'ASU-ROG-15',
            'sku' => 'SKU-ASU-001',
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
        ]);
        
        // Settings
        \App\Models\Setting::create(['key' => 'store_name', 'value' => 'PORTÁTILES PERÚ / MOYO TECH', 'type' => 'string']);
        \App\Models\Setting::create(['key' => 'whatsapp_number', 'value' => '+51999999999', 'type' => 'string']);
    }
}
