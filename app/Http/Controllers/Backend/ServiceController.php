<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\{DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Category, Service};
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.services.index');
    }

    public function getData(Request $request)
    {
        $rows = Service::with('category:id,name')->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()

            // Kolom name -> jadi link detail
            ->editColumn('name', function ($row) {
                $url = route('service.show', $row->id);
                $nameService = e($row->name);
                return '<a href="' . $url . '" class="text-primary text-decoration-none"
                data-size="xl" data-toggle="ajaxModal"
                data-title="Detail Service ' . $nameService . '">' . $nameService . '</a>';
            })

            ->addColumn('category_name', function ($row) {
                return $row->category
                    ? '<span class="badge bg-primary">' . e($row->category->name) . '</span>'
                    : '<span class="badge bg-secondary">-</span>';
            })

            ->editColumn('base_price', function ($row) {
                return Number::currency($row->base_price, 'IDR', 'id', 0);
            })

            ->editColumn('estimated_duration', function ($row) {
                $hours = floor($row->estimated_duration / 60);
                $minutes = $row->estimated_duration % 60;
                return $hours > 0
                    ? "{$hours}h {$minutes}m"
                    : "{$minutes}m";
            })

            ->editColumn('vehicle_type', function ($row) {
                switch ($row->vehicle_type) {
                    case 'car':
                        return '<span class="badge bg-info">Car</span>';
                    case 'truck':
                        return '<span class="badge bg-warning">Truck</span>';
                    default:
                        return '<span class="badge bg-primary">Car & Truck</span>';
                }
            })

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            ->addColumn('action', function ($row) {
                $editUrl = route('service.edit', $row->id);
                $deleteUrl = route('service.destroy', $row->id);

                return '
                <div class="d-flex justify-content-start gap-1">
                    <a href="' . $editUrl . '" class="btn btn-warning"
                       data-toggle="ajaxModal" data-title="Service | Edit"  data-size="lg">
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

            ->rawColumns(['name', 'category_name', 'vehicle_type', 'is_active', 'action'])
            ->make();
    }

    public function create()
    {
        $data['category'] = Category::where('is_active', true)->get();

        return view('app.backend.pages.services.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'category_id'        => 'required|exists:categories,id',
            'code'               => 'required|string|max:255|unique:services,code',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'base_price'         => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'vehicle_type'       => 'required|in:car,truck,both',
            'is_active'          => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $status = $request->has('is_active') ? 1 : 0;

            Service::create([
                'category_id'        => $request->category_id,
                'code'               => $request->code,
                'name'               => $request->name,
                'description'        => $request->description,
                'base_price'         => $request->base_price,
                'estimated_duration' => $request->estimated_duration,
                'vehicle_type'       => $request->vehicle_type,
                'is_active'          => $status,
            ]);

            DB::commit();
            return Message::created($request, "Service created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create service: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['service'] = Service::with('category')->findOrFail($id);
        $data['category'] = Category::all();

        return view('app.backend.pages.services.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'category_id'        => 'required|exists:categories,id',
            'code'               => 'required|string|max:255|unique:services,code,' . $id,
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'base_price'         => 'required|numeric|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'vehicle_type'       => 'required|in:car,truck,both',
            'is_active'          => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $service = Service::findOrFail($id);
            $status = $request->has('is_active') ? 1 : 0;

            $service->update([
                'category_id'        => $request->category_id,
                'code'               => $request->code,
                'name'               => $request->name,
                'description'        => $request->description,
                'base_price'         => $request->base_price,
                'estimated_duration' => $request->estimated_duration,
                'vehicle_type'       => $request->vehicle_type,
                'is_active'          => $status,
            ]);

            DB::commit();
            return Message::updated($request, "Service updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update service: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return Message::deleted($request, "Service deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to delete service: " . $e->getMessage());
        }
    }
}
