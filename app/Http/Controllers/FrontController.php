<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FrontController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::with(['brand', 'category', 'images'])->where('status', true)->where('is_featured', true)->take(10)->get();
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::with(['brand', 'category', 'images'])->where('status', true)->take(10)->get();
        }

        $offerProducts = Product::with(['brand', 'category', 'images'])->where('status', true)->where('is_offer', true)->take(6)->get();
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $sliders = Slider::where('status', true)->orderBy('order')->get();

        return view('front.home', compact('featuredProducts', 'offerProducts', 'categories', 'brands', 'sliders'));
    }

    public function catalog(Request $request)
    {
        $query = Product::with(['brand', 'category', 'deviceModel', 'images'])->where('status', true);

        // Búsqueda
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%')
                    ->orWhere('code', 'like', '%'.$request->q.'%');
            });
        }

        // Filtro por categoría
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro por marca
        if ($request->filled('brand')) {
            $query->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Filtro por ofertas
        if ($request->boolean('offers')) {
            $query->where('is_offer', true);
        }

        // Filtro por disponibilidad
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Rango de precio
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('is_featured', 'desc')->orderBy('stock', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $minPrice = Product::where('status', true)->min('price') ?? 0;
        $maxPrice = Product::where('status', true)->max('price') ?? 10000;

        return view('front.catalog', compact('products', 'categories', 'brands', 'minPrice', 'maxPrice', 'sort'));
    }

    public function show($slug)
    {
        $product = Product::with(['brand', 'category', 'deviceModel', 'images'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        $reviews = $product->reviews()->latest()->paginate(8, ['*'], 'opiniones');

        // Productos relacionados (misma categoría o marca)
        $relatedProducts = Product::with(['brand', 'category', 'images'])
            ->where('status', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->orWhere('brand_id', $product->brand_id);
            })
            ->take(4)
            ->get();

        return view('front.product', compact('product', 'relatedProducts', 'reviews'));
    }

    public function downloadPdf($slug)
    {
        $product = Product::with(['brand', 'category', 'deviceModel'])->where('slug', $slug)->firstOrFail();
        $settings = Setting::pluck('value', 'key');
        
        $pdf = Pdf::loadView('front.pdf.product', compact('product', 'settings'));
        
        return $pdf->download('Ficha_Tecnica_' . $product->slug . '.pdf');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_phone' => ['required', 'string', 'regex:/^[0-9+\\s().-]{7,20}$/'],
            'receipt_type' => ['required', 'in:boleta,factura'],
            'tax_id' => ['nullable', 'required_if:receipt_type,factura', 'digits:11'],
            'sale_note' => ['nullable', 'string', 'max:1000'],
            'cart' => ['required', 'array', 'min:1', 'max:30'],
            'cart.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $order = DB::transaction(function () use ($validated): Order {
            $requestedItems = collect($validated['cart'])->keyBy('product_id');
            $products = Product::query()
                ->whereIn('id', $requestedItems->keys())
                ->where('status', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            abort_if($products->count() !== $requestedItems->count(), 422, 'Uno o más productos ya no están disponibles.');

            $items = $requestedItems->map(function (array $cartItem, string $productId) use ($products): array {
                $product = $products->get((int) $productId);
                $quantity = (int) $cartItem['quantity'];

                abort_if($product->stock < $quantity, 422, 'No hay stock suficiente para '.$product->name.'.');

                $unitPrice = (float) ($product->is_offer && $product->offer_price ? $product->offer_price : $product->price);

                return [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => round($unitPrice * $quantity, 2),
                ];
            })->values()->all();

            $client = Client::updateOrCreate(
                ['phone' => $validated['client_phone']],
                ['name' => $validated['client_name']]
            );

            return Order::create([
                'client_id' => $client->id,
                'total_amount' => collect($items)->sum('subtotal'),
                'status' => 'Pendiente',
                'details' => [
                    'items' => $items,
                    'receipt_type' => $validated['receipt_type'],
                    'tax_id' => $validated['tax_id'] ?? null,
                    'sale_note' => $validated['sale_note'] ?? null,
                ],
            ]);
        });

        $whatsappNumber = preg_replace('/[^0-9]/', '', Setting::where('key', 'whatsapp_number')->value('value') ?? '');
        $receiptLabel = $validated['receipt_type'] === 'factura' ? 'Factura (RUC '.$validated['tax_id'].')' : 'Boleta';
        $messageLines = [
            'Hola, quiero confirmar el pedido #'.$order->id.'.',
            'Cliente: '.$validated['client_name'],
            'Celular: '.$validated['client_phone'],
            'Comprobante: '.$receiptLabel,
            '',
            'Productos:',
        ];

        foreach ($order->details['items'] as $item) {
            $messageLines[] = $item['quantity'].' × '.$item['name'].' — S/ '.number_format($item['subtotal'], 2);
        }

        $messageLines[] = 'Total: S/ '.number_format((float) $order->total_amount, 2);

        if (! empty($validated['sale_note'])) {
            $messageLines[] = 'Nota: '.$validated['sale_note'];
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => 'Pedido guardado correctamente.',
            'whatsapp_url' => 'https://wa.me/'.$whatsappNumber.'?text='.urlencode(implode("\n", $messageLines)),
        ]);
    }

    public function review(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->where('status', true)->firstOrFail();

        $validated = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:120'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        ProductReview::create([
            ...$validated,
            'product_id' => $product->id,
        ]);

        return redirect(route('product.show', $product->slug) . '#opiniones')->with('review_submitted', 'Gracias por compartir tu opinión.');
    }
}
