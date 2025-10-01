<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.master.supplier.index');
    }

    public function getData(Request $request)
    {
        $rows = Supplier::select(['*'])->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->editColumn('lead_time_days', function ($row) {
                return $row->lead_time_days . ' Days';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('master.supplier.edit', $row->id);
                $deleteUrl = route('master.supplier.destroy', $row->id);

                return '
        <div class="d-flex justify-content-start gap-1">
            <a href="' . $editUrl . '" class="btn btn-warning"
               data-toggle="ajaxModal" data-title="Supplier | Edit">
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

            ->rawColumns(['is_active', 'action'])
            ->make();
    }

    public function create()
    {
        return view('app.backend.pages.master.supplier.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'required|string',
            'lead_time_days'  => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $status = $request->has('is_active') ? 1 : 0;

            Supplier::create([
                'name'           => $request->name,
                'contact_person' => $request->contact_person,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'address'        => $request->address,
                'lead_time_days' => $request->lead_time_days,
                'is_active'      => $status,
            ]);

            DB::commit();

            return Message::created($request, "Supplier created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create supplier" . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['supplier'] = Supplier::where('id', $id)->firstOrFail();

        return view('app.backend.pages.master.supplier.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'code'            => 'required|string|max:255|unique:suppliers,code,' . $id,
            'name'            => 'required|string|max:255',
            'contact_person'  => 'nullable|string|max:255',
            'phone'           => 'required|string|max:20',
            'email'           => 'nullable|email|max:255',
            'address'         => 'required|string',
            'lead_time_days'  => 'required|integer|min:1',
            'is_active'       => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $supplier = Supplier::findOrFail($id);

            $status = $request->has('is_active') ? 1 : 0;

            $supplier->update([
                'code'           => $request->code,
                'name'           => $request->name,
                'contact_person' => $request->contact_person,
                'phone'          => $request->phone,
                'email'          => $request->email,
                'address'        => $request->address,
                'lead_time_days' => $request->lead_time_days,
                'is_active'      => $status,
            ]);

            DB::commit();

            return Message::updated($request, "Supplier updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update supplier" . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = Supplier::findOrFail($id);
            $category->delete();

            return Message::deleted($request, "Category deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to update category: " . $e->getMessage());
        }
    }
}
