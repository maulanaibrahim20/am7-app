<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Booking, User};
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
                $editUrl   = url('booking.edit', $row->id);
                $deleteUrl = url('booking.destroy', $row->id);

                return '
                <div class="d-flex justify-content-start gap-1">
                    <a href="' . $editUrl . '" class="btn btn-warning"
                       data-toggle="ajaxModal" data-title="Booking | Edit">
                        <i class="fas fa-pencil"></i>
                    </a>
                   <form action="' . $deleteUrl . '" method="POST"
                          id="ajxFormDelete" class="m-0 p-0">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            ';
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
}
