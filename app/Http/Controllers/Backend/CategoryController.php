<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.master.category.index');
    }

    public function getData(Request $request)
    {
        $rows = Category::select(['*'])->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->editColumn('type', function ($row) {
                return $row->type === 'product'
                    ? '<span class="badge bg-info">Product</span>'
                    : '<span class="badge bg-warning">Service</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('master.category.edit', $row->id);
                $deleteUrl = route('master.category.destroy', $row->id);

                return '
                    <div class="d-flex justify-content-start gap-1">
                        <a href="' . $editUrl . '" class="btn btn-warning"
                           data-toggle="ajaxModal" data-title="Category | Edit">
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

            ->rawColumns(['is_active', 'type', 'action'])
            ->make();
    }

    public function create()
    {
        return view('app.backend.pages.master.category.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'type'        => 'required|in:product,service',
            'is_active'   => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $status = $request->has('is_active') ? 1 : 0;

            Category::create([
                'name'        => $request->name,
                'slug'        => $request->slug,
                'description' => $request->description,
                'type'        => $request->type,
                'is_active'   => $status,
            ]);

            DB::commit();

            return Message::created($request, "Category created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create category" . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['category'] = Category::findOrFail($id);

        return view('app.backend.pages.master.category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'type'        => 'required|in:product,service',
            'is_active'   => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $category = Category::findOrFail($id);

            $status = $request->has('is_active') ? 1 : 0;

            $category->update([
                'name'        => $request->name,
                'slug'        => $request->slug,
                'description' => $request->description,
                'type'        => $request->type,
                'is_active'   => $status,
            ]);

            DB::commit();

            return Message::updated($request, "Category updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update category: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return Message::deleted($request, "Category deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to update category: " . $e->getMessage());
        }
    }
}
