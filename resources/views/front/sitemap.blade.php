{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <!-- Página Principal -->
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Catálogo General -->
    <url>
        <loc>{{ route('catalog') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Categorías -->
    @foreach($categories as $category)
    <url>
        <loc>{{ route('catalog', ['category' => $category->slug]) }}</loc>
        <lastmod>{{ $category->updated_at ? $category->updated_at->toDateString() : now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Marcas -->
    @foreach($brands as $brand)
    <url>
        <loc>{{ route('catalog', ['brand' => $brand->slug]) }}</loc>
        <lastmod>{{ $brand->updated_at ? $brand->updated_at->toDateString() : now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    <!-- Productos individuales con soporte de Google Images -->
    @foreach($products as $product)
    <url>
        <loc>{{ route('product.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at ? $product->updated_at->toDateString() : now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
        @if($product->images->isNotEmpty())
            @foreach($product->images as $img)
                @php
                    $imgUrl = filter_var($img->image_path, FILTER_VALIDATE_URL) ? $img->image_path : asset('storage/' . $img->image_path);
                @endphp
                <image:image>
                    <image:loc>{{ $imgUrl }}</image:loc>
                    <image:title>{{ htmlspecialchars($product->name, ENT_XML1, 'UTF-8') }}</image:title>
                    <image:caption>{{ htmlspecialchars(Str::limit(strip_tags($product->description ?: $product->name), 150), ENT_XML1, 'UTF-8') }}</image:caption>
                </image:image>
            @endforeach
        @endif
    </url>
    @endforeach
</urlset>
