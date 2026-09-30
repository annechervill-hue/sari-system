<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pos', compact('products'));
    }

    public function products()
    {
        return response()->json(Product::all());
    }

    public function storeSale(Request $request)
    {
        $items = $request->items;
        $discount = $request->discount ?? 0;

        $subtotal = 0;
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 400);
            }
            if ($product->quantity < $item['quantity']) {
                return response()->json(['success' => false, 'message' => 'Not enough stock for ' . $product->name], 400);
            }
            $subtotal += $product->price * $item['quantity'];
        }

        $discountAmount = $subtotal * ($discount / 100);
        $total = $subtotal - $discountAmount;

        $sale = Sale::create([
            'receipt_no' => 'POS-' . time(),
            'subtotal' => $subtotal,
            'discount' => $discountAmount,
            'total' => $total,
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $lineTotal = $product->price * $item['quantity'];

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price,
                'total' => $lineTotal,
            ]);

            $product->decrement('quantity', $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'receipt_no' => $sale->receipt_no,
            'total' => $total
        ]);
    }

    public function reports(Request $request)
    {
        $sales = Sale::query();

        if ($request->start_date) {
            $sales->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $sales->whereDate('created_at', '<=', $request->end_date);
        }

        return response()->json($sales->orderBy('created_at', 'desc')->get());
    }

    public function updatePrice(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->price = $request->price;
        $product->save();

        return response()->json(['success' => true]);
    }
}