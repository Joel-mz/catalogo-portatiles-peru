<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('client')->latest();

        if ($search = $request->input('search')) {
            $cleanId = preg_replace('/^#?0*/', '', $search);
            
            $query->where(function($q) use ($search, $cleanId) {
                if (is_numeric($cleanId)) {
                    $q->where('id', $cleanId);
                }
                $q->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->appends(['search' => $search]);

        return view('admin.orders.index', compact('orders', 'search'));
    }

    public function show(Order $order)
    {
        $order->load('client');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:Pendiente,Contactado,Completado,Cancelado',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Estado del pedido actualizado.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pedido eliminado exitosamente.');
    }

    public function downloadPdf(Order $order)
    {
        $order->load('client');
        $settings = Setting::pluck('value', 'key');
        
        $pdf = Pdf::loadView('admin.orders.pdf', compact('order', 'settings'));
        
        return $pdf->download('Pedido_Nro_' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
