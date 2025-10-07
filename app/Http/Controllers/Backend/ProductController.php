<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use Illuminate\Support\Number;
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{Category, Product, Supplier};
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.product.index');
    }

    public function contoh()
    {
        $data = [
            'products' => Product::all(),
            'category' => Category::all()
        ];
        return view('app.backend.pages.product.index_bak', $data);
    }
    public function getData(Request $request)
    {
        $rows = Product::with('category:id,name')->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()

            ->editColumn('name', function ($row) {
                $url = route('product.show', $row->id);
                $nameProduct = $row->name;
                return '<a href="' . $url . '" class="text-primary text-decoration-none" data-size="xl" data-toggle="ajaxModal"
                data-title="Detail Product ' . $nameProduct . '">' . $row->name . '</a>';
            })
            ->addColumn('category_name', function ($row) {
                return $row->category
                    ? '<span class="badge bg-primary">' . e($row->category->name) . '</span>'
                    : '<span class="badge bg-secondary">-</span>';
            })
            ->editColumn('stock_quantity', function ($row) {
                if ($row->stock_quantity <= $row->min_stock) {
                    return '<span class="badge bg-danger">' . $row->stock_quantity . '</span>';
                } elseif ($row->stock_quantity >= $row->max_stock) {
                    return '<span class="badge bg-warning">' . $row->stock_quantity . '</span>';
                }
                return '<span class="badge bg-success">' . $row->stock_quantity . '</span>';
            })

            ->editColumn('purchase_price', function ($row) {
                return Number::currency($row->purchase_price, 'IDR', 'id', 0);
            })
            ->editColumn('selling_price', function ($row) {
                return Number::currency($row->selling_price, 'IDR', 'id', 0);
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('product.edit', $row->id);
                $deleteUrl = route('product.destroy', $row->id);

                return '
                <div class="d-flex justify-content-start gap-1">
                    <a href="' . $editUrl . '" class="btn btn-warning"
                       data-toggle="ajaxModal" data-title="Product | Edit"  data-size="lg">
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

            ->rawColumns(['name', 'category_name', 'stock_quantity', 'is_active', 'action'])
            ->make();
    }

    public function create()
    {
        $data['category'] = Category::where('is_active', true)->where('type', 'product')->get();
        $data['supplier'] = Supplier::where('is_active', true)->get();

        return view('app.backend.pages.product.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'category_id'       => 'required|exists:categories,id',
            'sku'               => 'required|string|max:255|unique:products,sku',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'unit'              => 'required|string|max:20',
            'purchase_price'    => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'min_stock'         => 'nullable|integer|min:0',
            'max_stock'         => 'nullable|integer|min:0',
            'reorder_point'     => 'nullable|integer|min:0',
            'reorder_quantity'  => 'nullable|integer|min:0',
            'avg_daily_usage'   => 'nullable|numeric|min:0',
            'lead_time_days'    => 'nullable|integer|min:0',
            'brand'             => 'nullable|string|max:255',
            'compatible_vehicles' => 'nullable|string|max:255',
            'is_active'         => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $status = $request->has('is_active') ? 1 : 0;

            Product::create([
                'category_id'       => $request->category_id,
                'sku'               => $request->sku,
                'name'              => $request->name,
                'description'       => $request->description,
                'unit'              => $request->unit,
                'purchase_price'    => $request->purchase_price,
                'selling_price'     => $request->selling_price,
                'stock_quantity'    => $request->stock_quantity,
                'min_stock'         => $request->min_stock ?? 5,
                'max_stock'         => $request->max_stock ?? 100,
                'reorder_point'     => $request->reorder_point ?? 10,
                'reorder_quantity'  => $request->reorder_quantity ?? 20,
                'avg_daily_usage'   => $request->avg_daily_usage ?? 0,
                'lead_time_days'    => $request->lead_time_days ?? 3,
                'brand'             => $request->brand,
                'compatible_vehicles' => $request->compatible_vehicles,
                'is_active'         => $status,
            ]);

            DB::commit();
            return Message::created($request, "Product created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create product: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data['product'] = Product::with(['category', 'suppliers'])->findOrFail($id);
        $data['suppliers'] = Supplier::all();

        return view('app.backend.pages.product.show', $data);
    }

    public function edit($id)
    {
        $data['product'] = Product::with('category')->findOrFail($id);
        $data['category'] = Category::where('is_active', true)->where('type', 'product')->get();

        return view('app.backend.pages.product.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'category_id'       => 'required|exists:categories,id',
            'sku'               => 'required|string|max:255|unique:products,sku,' . $id,
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'unit'              => 'required|string|max:20',
            'purchase_price'    => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'stock_quantity'    => 'required|integer|min:0',
            'min_stock'         => 'nullable|integer|min:0',
            'max_stock'         => 'nullable|integer|min:0',
            'reorder_point'     => 'nullable|integer|min:0',
            'reorder_quantity'  => 'nullable|integer|min:0',
            'avg_daily_usage'   => 'nullable|numeric|min:0',
            'lead_time_days'    => 'nullable|integer|min:0',
            'brand'             => 'nullable|string|max:255',
            'compatible_vehicles' => 'nullable|string|max:255',
            'is_active'         => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $product = Product::findOrFail($id);
            $status = $request->has('is_active') ? 1 : 0;

            $product->update([
                'category_id'       => $request->category_id,
                'sku'               => $request->sku,
                'name'              => $request->name,
                'description'       => $request->description,
                'unit'              => $request->unit,
                'purchase_price'    => $request->purchase_price,
                'selling_price'     => $request->selling_price,
                'stock_quantity'    => $request->stock_quantity,
                'min_stock'         => $request->min_stock ?? 5,
                'max_stock'         => $request->max_stock ?? 100,
                'reorder_point'     => $request->reorder_point ?? 10,
                'reorder_quantity'  => $request->reorder_quantity ?? 20,
                'avg_daily_usage'   => $request->avg_daily_usage ?? 0,
                'lead_time_days'    => $request->lead_time_days ?? 3,
                'brand'             => $request->brand,
                'compatible_vehicles' => $request->compatible_vehicles,
                'is_active'         => $status,
            ]);

            DB::commit();
            return Message::updated($request, "Product updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update product: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = Product::findOrFail($id);
            $category->delete();

            return Message::deleted($request, "Product deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to delete product: " . $e->getMessage());
        }
    }

    public function addSupplier(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'supplier_id'    => 'required|exists:suppliers,id',
            'supplier_price' => 'required|numeric|min:0',
            'min_order_qty'  => 'required|integer|min:1',
            'is_primary'     => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $product = Product::findOrFail($id);

            $isPrimary = $request->has('is_primary') ? 1 : 0;

            if ($isPrimary) {
                $product->suppliers()->updateExistingPivot(
                    $product->suppliers->pluck('id')->toArray(),
                    ['is_primary' => false]
                );
            }

            $product->suppliers()->attach($request->supplier_id, [
                'supplier_price' => $request->supplier_price,
                'min_order_qty'  => $request->min_order_qty,
                'is_primary'     => $isPrimary,
            ]);

            DB::commit();
            return Message::created($request, "Supplier added successfully to product");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to add supplier: " . $e->getMessage());
        }
    }
}
