<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.customer.index');
    }

    public function getData(Request $request)
    {
        $rows = Customer::select(['*'])->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('total_spent', function ($row) {
                return 'Rp ' . number_format($row->total_spent, 0, ',', '.');
            })
            ->editColumn('visit_count', function ($row) {
                return '<span class="badge bg-info">' . $row->visit_count . 'x</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('customer.edit', $row->id);
                $deleteUrl = route('customer.destroy', $row->id);

                return '
                <div class="d-flex justify-content-start gap-1">
                    <a href="' . $editUrl . '" class="btn btn-warning"
                       data-toggle="ajaxModal" data-title="Customer | Edit">
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
            ->rawColumns(['visit_count', 'action'])
            ->make();
    }

    public function create()
    {
        return view('app.backend.pages.customer.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20|unique:customers,phone',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_type'   => 'nullable|string|max:100',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            Customer::create([
                'name'           => $request->name,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'address'        => $request->address,
                'vehicle_number' => $request->vehicle_number,
                'vehicle_type'   => $request->vehicle_type,
                'total_spent'    => 0,
                'visit_count'    => 0,
            ]);

            DB::commit();
            return Message::created($request, "Customer created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create customer: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['customer'] = Customer::findOrFail($id);

        return view('app.backend.pages.customer.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20|unique:customers,phone,' . $id,
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'vehicle_number' => 'nullable|string|max:50',
            'vehicle_type'   => 'nullable|string|max:100',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $customer = Customer::findOrFail($id);

            $customer->update([
                'name'           => $request->name,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'address'        => $request->address,
                'vehicle_number' => $request->vehicle_number,
                'vehicle_type'   => $request->vehicle_type,
            ]);

            DB::commit();
            return Message::updated($request, "Customer updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update customer: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return Message::deleted($request, "Customer deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to update Customer: " . $e->getMessage());
        }
    }
}
