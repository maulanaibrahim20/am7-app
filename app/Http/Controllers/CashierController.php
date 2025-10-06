<?php

namespace App\Http\Controllers;

use App\Facades\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Models\{CartItem, CartSession, Category, Customer, Product, Sale, SaleItem, Service, StockMovement};

class CashierController extends Controller
{
    public function index()
    {
        $data['product'] = Product::with('category')
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name', 'asc')
            ->paginate(9, ['*'], 'product_page') // ✅ page khusus product
            ->appends([
                'type' => request('type', 'product'),
            ]);

        $data['services'] = Service::where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(9, ['*'], 'service_page') // ✅ page khusus service
            ->appends([
                'type' => request('type', 'service'),
            ]);

        $data['categories'] = Category::where('type', 'product')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $data['currentCart'] = CartSession::with(['customer', 'items.cartable'])
            ->where('cashier_id', Auth::id())
            ->where('status', 'active')
            ->first();

        $data['categories'] = Category::where('type', 'product')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $data['customers'] = Customer::orderBy('name', 'asc')->get();

        $data['heldCartsCount'] = CartSession::where('cashier_id', Auth::id())
            ->where('status', 'hold')
            ->count();

        return view('app.backend.pages.cashier.index', $data);
    }

    public function show($id, $type)
    {
        if ($type === 'product') {
            $product = Product::findOrFail($id);
            return view('app.backend.pages.cashier.detail.show_product', compact('product'));
        } else {
            $service = Service::findOrFail($id);
            return view('app.backend.pages.cashier.detail.show_service', compact('service'));
        }
    }

    public function getSelectCustomer()
    {
        $data['customers'] = Customer::orderBy('name', 'asc')->get();
        return view('app.backend.pages.cashier.select-customer', $data);
    }

    public function selectCustomer(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:20',
            'customer_email' => 'nullable|email',
            'customer_address' => 'nullable|string',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $existingCart = CartSession::where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if ($existingCart) {
                return Message::error($request, 'You already have an active cart', [], null);
            }

            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                ]);
            }

            $sessionCode = CartSession::generateSessionCode();

            $cartSession = CartSession::create([
                'session_code' => $sessionCode,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'cashier_id' => Auth::id(),
                'status' => 'active',
            ]);

            DB::commit();

            return Message::success($request, 'Customer selected successfully', [], null);
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request, 'Failed to select customer: ' . $e->getMessage());
        }
    }

    public function addToCart(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'item_type' => 'required|in:product,service',
            'item_id' => 'required|integer',
            'quantity' => 'nullable|integer|min:1',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $cartSession = CartSession::where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if (!$cartSession) {
                return Message::error($request, 'Please select a customer first.');
            }

            $quantity = $request->quantity ?? 1;

            if ($request->item_type === 'product') {
                $item = Product::findOrFail($request->item_id);

                if ($item->stock_quantity < $quantity) {
                    return Message::error($request, "Insufficient stock. Available: {$item->stock_quantity}");
                }

                $itemName = $item->name;
                $unitPrice = $item->selling_price;
                $itemClass = Product::class;
            } else {
                $item = Service::findOrFail($request->item_id);
                $itemName = $item->name;
                $unitPrice = $item->base_price;
                $itemClass = Service::class;
            }

            // Check if item already exists in cart
            $existingItem = CartItem::where('cart_session_id', $cartSession->id)
                ->where('cartable_type', $itemClass)
                ->where('cartable_id', $request->item_id)
                ->first();

            if ($existingItem) {
                // Update quantity
                $existingItem->quantity += $quantity;
                $existingItem->calculateSubtotal();
                $existingItem->save();
            } else {
                // Create new cart item
                $cartItem = new CartItem([
                    'cart_session_id' => $cartSession->id,
                    'cartable_id' => $request->item_id,
                    'cartable_type' => $itemClass,
                    'item_name' => $itemName,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_percent' => 0,
                ]);

                $cartItem->calculateSubtotal();
                $cartItem->save();
            }

            DB::commit();

            return Message::success($request,  "{$itemName} added to cart");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request, "Failed to add item: " . $e->getMessage());
        }
    }

    public function viewCart(Request $request)
    {
        $cartSession = CartSession::with(['customer', 'items.cartable'])
            ->where('cashier_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (!$cartSession) {
            return Message::notFound($request, 'Cart not found');
        }

        return view('app.backend.pages.cashier.cart', compact('cartSession'));
    }

    public function updateCartItem(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $cartItem = CartItem::findOrFail($id);

            if ($cartItem->cartable_type === Product::class) {
                $product = $cartItem->cartable;
                if ($product->stock_quantity < $request->quantity) {

                    return Message::error($request, "Insufficient stock. Available: {$product->stock_quantity}");
                }
            }

            $cartItem->quantity = $request->quantity;

            if ($request->has('discount_percent')) {
                $cartItem->discount_percent = $request->discount_percent;
            }

            $cartItem->calculateSubtotal();
            $cartItem->save();

            DB::commit();

            return Message::success($request, 'Cart item updated');
        } catch (\Exception $e) {
            DB::rollback();

            return Message::error($request, 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function removeCartItem(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();

            DB::commit();

            return Message::success($request, 'Item removed from cart');
        } catch (\Exception $e) {
            DB::rollback();
            return Message::success($request, 'Failed to remove item: ' . $e->getMessage());
        }
    }

    public function holdModal(Request $request)
    {
        return view('app.backend.pages.cashier.hold');
    }
    public function holdCart(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
            'expired_hours' => 'nullable|integer|min:1|max:72',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $cartSession = CartSession::where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if (!$cartSession) {
                return Message::error($request, 'No active cart to hold');
            }

            if ($cartSession->items()->count() === 0) {
                return Message::error($request, 'Cannot hold empty cart');
            }

            $expiredHours = (int) ($request->expired_hours ?? 24);

            $cartSession->update([
                'status' => 'hold',
                'notes' => $request->notes,
                'hold_at' => now(),
                'expired_at' => now()->addHours($expiredHours),
            ]);

            DB::commit();

            return Message::success($request, 'Cart held successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request, 'Failed to hold cart: ' . $e->getMessage());
        }
    }

    public function heldCarts()
    {
        $heldCarts = CartSession::with(['customer', 'items'])
            ->where('cashier_id', Auth::id())
            ->where('status', 'hold')
            ->orderBy('hold_at', 'desc')
            ->get();

        return view('app.backend.pages.cashier.held', compact('heldCarts'));
    }

    public function resumeCart(Request $request, $sessionCode)
    {
        DB::beginTransaction();

        try {
            $existingCart = CartSession::where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if ($existingCart) {
                return Message::notFound($request, 'Please hold or checkout your current cart first');
            }

            $cartSession = CartSession::where('session_code', $sessionCode)
                ->where('cashier_id', Auth::id())
                ->where('status', 'hold')
                ->firstOrFail();

            if ($cartSession->expired_at && $cartSession->expired_at->isPast()) {
                return Message::error($request, 'Cart session has expired');
            }

            $cartSession->update([
                'status' => 'active',
                'hold_at' => null,
            ]);

            DB::commit();

            return Message::success($request, "Cart resumed: {$sessionCode}");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request,  'Failed to resume cart: ' . $e->getMessage());
        }
    }


    public function cancelCart(Request $request, $sessionCode)
    {
        DB::beginTransaction();

        try {
            $cartSession = CartSession::where('session_code', $sessionCode)
                ->where('cashier_id', Auth::id())
                ->whereIn('status', ['active', 'hold'])
                ->firstOrFail();

            $cartSession->update([
                'status' => 'cancelled',
            ]);

            DB::commit();

            return Message::success($request, "Cart cancelled successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request,  'Failed to cancel cart: ' . $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $cartSession = CartSession::with(['customer', 'items.cartable'])
            ->where('cashier_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (!$cartSession) {
            return Message::notFound($request, 'No active cart to checkout');
        }

        if ($cartSession->items()->count() === 0) {
            return Message::notFound($request, 'Cart is empty');
        }

        return view('app.backend.pages.cashier.checkout', compact('cartSession'));
    }

    public function processPayment(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:cash,transfer,card',
            'cash_received' => 'required_if:payment_method,cash|nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $cartSession = CartSession::with(['customer', 'items.cartable'])
                ->where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if (!$cartSession) {
                return Message::notFound($request, 'No active cart found');
            }

            if ($cartSession->items()->count() === 0) {
                return Message::notFound($request, 'Cart is empty');
            }

            $subtotal = $cartSession->getTotalAmount();
            $taxAmount = 0;
            $totalAmount = $subtotal + $taxAmount;

            if ($request->payment_method === 'cash') {
                $cashReceived = $request->cash_received;
                $cashReceived = (int) str_replace('.', '', $cashReceived);

                if ($cashReceived < $totalAmount) {

                    return Message::error($request, 'Cash received is less than total amount');
                }

                $changeAmount = $cashReceived - $totalAmount;
                $paidAmount = $cashReceived;
            } else {
                $changeAmount = 0;
                $paidAmount = $totalAmount;
            }

            $invoiceNumber = $this->generateInvoiceNumber();

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $cartSession->customer_id,
                'booking_id' => $cartSession->booking_id,
                'cashier_id' => Auth::id(),
                'transaction_type' => 'from_hold',
                'hold_reference' => $cartSession->status === 'hold' ? $cartSession->session_code : null,
                'subtotal' => $subtotal,
                'discount_amount' => $cartSession->items->sum('discount_amount'),
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
                'notes' => $request->notes,
            ]);

            foreach ($cartSession->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'saleable_id' => $item->cartable_id,
                    'saleable_type' => $item->cartable_type,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount_percent' => $item->discount_percent,
                    'discount_amount' => $item->discount_amount,
                    'subtotal' => $item->subtotal,
                    'mechanic_id' => $item->mechanic_id,
                ]);

                if ($item->cartable_type === Product::class) {
                    $product = $item->cartable;

                    if ($product->stock_quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock_quantity}");
                    }

                    $stockBefore = $product->stock_quantity;
                    $stockAfter = $stockBefore - $item->quantity;

                    $product->update([
                        'stock_quantity' => $stockAfter,
                    ]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'unit_cost' => $product->purchase_price,
                        'reference_type' => Sale::class,
                        'reference_id' => $sale->id,
                        'reason' => "Sale - Invoice: {$invoiceNumber}",
                        'created_by' => Auth::id(),
                    ]);

                    $this->updateProductUsage($product);
                }
            }

            $customer = $cartSession->customer;
            $customer->increment('visit_count');
            $customer->increment('total_spent', $totalAmount);

            $cartSession->update([
                'status' => 'converted',
            ]);

            DB::commit();

            return Message::success($request, 'Payment processed successfully', [], route('cashier.invoice', $sale->id));
        } catch (\Exception $e) {
            DB::rollback();
            return Message::error($request, 'Failed to process payment: ' . $e->getMessage());
        }
    }

    private function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $lastSale = Sale::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSale ? (int)substr($lastSale->invoice_number, -4) + 1 : 1;

        return 'INV-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function updateProductUsage($product)
    {
        // Get sales from last 30 days
        $thirtyDaysAgo = now()->subDays(30);

        $totalSold = SaleItem::where('saleable_type', Product::class)
            ->where('saleable_id', $product->id)
            ->whereHas('sale', function ($q) use ($thirtyDaysAgo) {
                $q->where('created_at', '>=', $thirtyDaysAgo);
            })
            ->sum('quantity');

        $avgDailyUsage = $totalSold / 30;

        $product->update([
            'avg_daily_usage' => round($avgDailyUsage, 2),
        ]);
    }

    public function invoice($id)
    {
        $sale = Sale::with([
            'customer',
            'cashier',
            'saleItems.saleable',
            'booking'
        ])->findOrFail($id);

        if ($sale->cashier_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        return view('app.backend.pages.cashier.invoice', compact('sale'));
    }

    public function printInvoice($id)
    {
        $sale = Sale::with([
            'customer',
            'cashier',
            'saleItems.saleable'
        ])->findOrFail($id);

        return view('app.backend.pages.cashier.print-invoice', compact('sale'));
    }
}
