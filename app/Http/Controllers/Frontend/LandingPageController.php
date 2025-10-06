<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Booking, Service};

class LandingPageController extends Controller
{
    public function home()
    {
        $data['services'] = Service::where('is_active', true)->get();

        return view('app.frontend.pages.home.index', $data);
    }

    public function booking()
    {
        return view('app.frontend.pages.home.booking');
    }

    public function bookingStore(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'customer_name'        => 'required|string|max:255',
            'customer_phone'       => 'required|string|max:20',
            'customer_email'       => 'nullable|email|max:255',
            'vehicle_type'         => 'required|string|max:255',
            'vehicle_number'       => 'required|string|max:255',
            'problem_description'  => 'required|string',
            'booking_date'         => 'required|date',
            'booking_time'         => 'required|date_format:H:i',
            'status'               => 'nullable|in:pending,approved,rejected,in_progress,completed,cancelled',
            'admin_notes'          => 'nullable|string',
            'mechanic_notes'       => 'nullable|string',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $lastBooking = Booking::latest()->first();
            $nextId = $lastBooking ? $lastBooking->id + 1 : 1;
            $bookingCode = 'BK-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            Booking::create([
                'booking_code'       => $bookingCode,
                'customer_name'      => $request->customer_name,
                'customer_phone'     => $request->customer_phone,
                'customer_email'     => $request->customer_email,
                'vehicle_type'       => $request->vehicle_type,
                'vehicle_number'     => $request->vehicle_number,
                'problem_description' => $request->problem_description,
                'booking_date'       => $request->booking_date,
                'booking_time'       => $request->booking_time,
                'status'             => $request->status ?? 'pending',
                'admin_notes'        => $request->admin_notes,
                'mechanic_notes'     => $request->mechanic_notes,
            ]);

            DB::commit();
            return Message::created($request, "Booking created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create booking: " . $e->getMessage());
        }
    }


    public function about()
    {
        return view('app.frontend.pages.about.index');
    }
    public function services()
    {
        $data['services'] = Service::where('is_active', true)->get();

        return view('app.frontend.pages.services.index', $data);
    }
    public function contact()
    {
        return view('app.frontend.pages.contact.index');
    }
}
