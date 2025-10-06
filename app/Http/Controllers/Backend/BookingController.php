<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Booking, CartItem, CartSession, Customer, User};
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.booking.index');
    }

    public function getData(Request $request)
    {
        $rows = Booking::select(['*'])->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('booking_code', function ($row) {
                $url = route('booking.show', $row->id);
                $bookingCode = e($row->booking_code);
                return '<a href="' . $url . '" class="text-primary text-decoration-none"
                data-size="xl" data-toggle="ajaxModal"
                data-title="Booking Service ' . $bookingCode . '">' . $bookingCode . '</a>';
            })
            ->editColumn('booking_date', function ($row) {
                return $row->booking_date ? Carbon::parse($row->booking_date)->format('d M Y') : '-';
            })
            ->editColumn('booking_time', function ($row) {
                return $row->booking_time ? Carbon::parse($row->booking_time)->format('H:i') : '-';
            })
            ->editColumn('status', function ($row) {
                $statusColors = [
                    'pending'     => 'secondary',
                    'approved'    => 'info',
                    'rejected'    => 'danger',
                    'in_progress' => 'warning',
                    'completed'   => 'success',
                    'cancelled'   => 'dark',
                ];
                $color = $statusColors[$row->status] ?? 'secondary';
                return '<span class="badge bg-' . $color . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                if ($row->status === 'in_progress') {
                    $deleteUrl = route('booking.loadFromBooking', $row->id);

                    return '
                    <div class="d-flex justify-content-start gap-1">
                        <form action="' . $deleteUrl . '" method="POST"
                            class="m-0 p-0">
                            ' . csrf_field() . '
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-share me-3"></i> Process to cashier
                            </button>
                        </form>
                    </div>
                ';
                } else {
                    return '<span class="badge bg-secondary">No Action</span>';
                }
            })
            ->rawColumns(['status', 'action', 'booking_code'])
            ->make();
    }

    public function show($id)
    {
        $data['booking'] = Booking::findOrFail($id);
        $data['mechanics'] = User::where('is_active', true)->whereHas('roles', function ($q) {
            $q->where('name', 'mechanic');
        })->get();

        return view('app.backend.pages.booking.show', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected,in_progress,completed,cancelled',
            'mechanic_id' => 'required_if:status,in_progress',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $booking = Booking::findOrFail($id);

            $oldStatus = $booking->status;

            $booking->status = $request->status;

            if ($request->status === 'approved') {
                $booking->approved_by = Auth::id();
                $booking->approved_at = now();
            }

            if ($request->status === 'in_progress' && !$booking->started_at) {
                $booking->started_at = now();
                $booking->mechanic_id = $request->mechanic_id;
            }

            if ($request->status === 'completed' && !$booking->completed_at) {
                $booking->completed_at = now();
            }

            $booking->save();

            DB::commit();

            return Message::updated($request, "Booking status updated from {$oldStatus} to {$booking->status}");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update booking status. " . $e->getMessage());
        }
    }

    public function notes(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'required_if:mechanic_notes,null',
            'mechanic_notes' => 'required_if:admin_notes,null',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $booking = Booking::findOrFail($id);

            if ($request->type == 'admin_notes') {
                $booking->admin_notes = $request->admin_notes;
            } else {
                $booking->mechanic_notes = $request->mechanic_notes;
            }

            $booking->save();

            return Message::updated($request, "Notes updated");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to update notes. " . $e->getMessage());
        }
    }

    public function loadFromBooking($bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::with('services')->findOrFail($bookingId);

            // Validasi status booking
            if (!in_array($booking->status, ['approved', 'in_progress'])) {
                return redirect()->back()->with('error', 'Booking belum di-approve atau sudah diproses.');
            }

            // Cek apakah booking sudah pernah dimuat ke cart
            $existingCart = CartSession::where('booking_id', $bookingId)
                ->whereIn('status', ['active', 'converted'])
                ->first();

            if ($existingCart && $existingCart->status === 'converted') {
                return redirect()->back()->with('error', 'Booking ini sudah pernah diproses.');
            }

            if ($existingCart && $existingCart->status === 'active') {
                return redirect()->route('cashier', $existingCart->id)
                    ->with('info', 'Booking ini sedang dalam proses.');
            }

            // Cari atau buat customer
            $customer = Customer::firstOrCreate(
                ['phone' => $booking->customer_phone],
                [
                    'name'  => $booking->customer_name,
                    'email' => $booking->customer_email,
                    'vehicle_number' => $booking->vehicle_number,
                    'vehicle_type'   => $booking->vehicle_type,
                ]
            );

            // Hold cart yang sedang aktif (jika ada)
            CartSession::where('cashier_id', Auth::id())
                ->where('status', 'active')
                ->update([
                    'status'  => 'hold',
                    'hold_at' => now(),
                ]);

            // Generate session code
            $sessionCode = 'CART-' . date('YmdHis') . '-' . Auth::id();

            // Buat cart session baru
            $cart = CartSession::create([
                'session_code'   => $sessionCode,
                'booking_id'     => $booking->id,
                'customer_id'    => $customer->id,
                'customer_name'  => $booking->customer_name,
                'customer_phone' => $booking->customer_phone,
                'cashier_id'     => Auth::id(),
                'status'         => 'active',
            ]);

            // Load services dari booking ke cart
            foreach ($booking->services as $service) {
                CartItem::create([
                    'cart_session_id' => $cart->id,
                    'cartable_type'   => 'App\Models\Service',
                    'cartable_id'     => $service->id,
                    'item_name'       => $service->name,
                    'quantity'        => 1,
                    'unit_price'      => $service->base_price,
                    'discount_percent' => 0,
                    'discount_amount' => 0,
                    'subtotal'        => $service->base_price,
                    'mechanic_id'     => $booking->mechanic_id,
                ]);
            }

            $booking->update([
                'status'     => 'completed',
                'started_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('cashier', $cart->id)
                ->with('success', 'Booking berhasil dimuat ke kasir.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses booking: ' . $e->getMessage());
        }
    }
}
