<?php

$c = App\Models\Category::firstOrCreate(['name'=>'Laptops'], ['slug'=>'laptops', 'description'=>'Laptops', 'status'=>1]); 
$lenovo = App\Models\Brand::firstOrCreate(['name'=>'Lenovo'], ['slug'=>'lenovo', 'status'=>1]); 
$hp = App\Models\Brand::firstOrCreate(['name'=>'HP'], ['slug'=>'hp', 'status'=>1]); 

App\Models\Product::updateOrCreate(
    ['code'=>'82YU00X5LM'], 
    [
        'name'=>'Lenovo V15 G4 AMN', 
        'slug'=>'lenovo-v15-g4-amn', 
        'sku'=>'LEN-82YU00X5LM',
        'serial_number'=>'PF629TWG', 
        'category_id'=>$c->id, 
        'brand_id'=>$lenovo->id, 
        'price'=>1299, 
        'stock'=>5, 
        'status'=>1, 
        'description'=>'AMD Athlon Silver 7120U, 8GB RAM, 256GB SSD, 15.6 FHD, NO OS'
    ]
); 

App\Models\Product::updateOrCreate(
    ['code'=>'83GW005NLD'], 
    [
        'name'=>'Lenovo V15 G5 IRL', 
        'slug'=>'lenovo-v15-g5-irl', 
        'sku'=>'LEN-83GW005NLD',
        'serial_number'=>'PF634481', 
        'category_id'=>$c->id, 
        'brand_id'=>$lenovo->id, 
        'price'=>1499, 
        'stock'=>5, 
        'status'=>1, 
        'description'=>'Intel i3-1315U, 8GB RAM, 256GB SSD, 15.6 FHD, NO OS'
    ]
); 

App\Models\Product::updateOrCreate(
    ['code'=>'82XQ00N5LM'], 
    [
        'name'=>'Lenovo IdeaPad Slim 3 15AMN8', 
        'slug'=>'lenovo-ideapad-slim-3', 
        'sku'=>'LEN-82XQ00N5LM',
        'serial_number'=>'PF5WQYD7', 
        'category_id'=>$c->id, 
        'brand_id'=>$lenovo->id, 
        'price'=>1799, 
        'stock'=>5, 
        'status'=>1, 
        'description'=>'AMD Ryzen 5 7520U, 8GB RAM, 512GB SSD, 15.6 FHD, NO OS'
    ]
); 

App\Models\Product::updateOrCreate(
    ['code'=>'B5U09AT#ABM'], 
    [
        'name'=>'HP 250 G10', 
        'slug'=>'hp-250-g10', 
        'sku'=>'HP-B5U09AT',
        'serial_number'=>'5CD5207MYY', 
        'category_id'=>$c->id, 
        'brand_id'=>$hp->id, 
        'price'=>1599, 
        'stock'=>5, 
        'status'=>1, 
        'description'=>'HP 250 G10 Laptop'
    ]
); 

App\Models\Product::updateOrCreate(
    ['code'=>'B9T22LA#ABM'], 
    [
        'name'=>'HP Laptop 15-fd0276la', 
        'slug'=>'hp-laptop-15-fd', 
        'sku'=>'HP-B9T22LA',
        'serial_number'=>'5CD5518YQD', 
        'category_id'=>$c->id, 
        'brand_id'=>$hp->id, 
        'price'=>1699, 
        'stock'=>5, 
        'status'=>1, 
        'description'=>'HP Laptop 15-fd0276la'
    ]
); 

echo "Products added successfully!\n";
