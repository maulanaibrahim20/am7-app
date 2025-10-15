<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use Illuminate\Support\Str;
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\Feature;
use Yajra\DataTables\DataTables;

class CmsFeatureController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('order')->get();
        $canAddMore = $features->count() < 3;
        return view('app.backend.pages.cms.feature.index', compact('features', 'canAddMore'));
    }

    public function getData(Request $request)
    {
        $rows = Feature::select('*')->orderBy('order', 'asc');

        return DataTables::of($rows)
            ->addIndexColumn()

            ->editColumn('icon', function ($row) {
                return '<i class="' . e($row->icon) . ' fa-2x text-danger"></i>';
            })

            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })

            ->editColumn('description', function ($row) {
                return Str::limit($row->description, 50);
            })

            ->editColumn('title', function ($row) {
                $html = '<strong>' . e($row->title) . '</strong>';
                if ($row->link_url) {
                    $html .= '<br><small class="text-muted"><i class="fas fa-link"></i> ' .
                        Str::limit($row->link_url, 30) . '</small>';
                }
                return $html;
            })

            ->addColumn('action', function ($row) {
                $editUrl = route('cms.feature.edit', $row->id);
                $deleteUrl = route('cms.feature.destroy', $row->id);

                return '
                    <div class="d-flex justify-content-start gap-1">
                        <a href="' . $editUrl . '" class="btn btn-warning"
                           data-toggle="ajaxModal" data-title="Feature | Edit" data-size="lg">
                            <i class="fas fa-pencil"></i>
                        </a>
                       <form action="' . $deleteUrl . '" method="POST"
                              id="ajxFormDelete" class="m-0 p-0" data-ajxFormDelete-reset="true">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                ';
            })

            ->rawColumns(['icon', 'title', 'is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('app.backend.pages.cms.feature.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'icon'             => 'required|string|max:255',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'link_text'        => 'nullable|string|max:255',
            'link_url'         => 'nullable|url|max:255',
            'background_style' => 'nullable|string|max:255',
            'is_active'        => 'nullable|in:0,1,true,false',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        if (Feature::count() >= 3) {
            return Message::error($request, "Maximum 5 features allowed!");
        }

        try {
            $status = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

            $maxOrder = Feature::max('order') ?? 0;

            Feature::create([
                'icon'             => $request->icon,
                'title'            => $request->title,
                'description'      => $request->description,
                'link_text'        => $request->link_text,
                'link_url'         => $request->link_url,
                'background_style' => $request->background_style ?? 'bg-light',
                'is_active'        => $status,
                'order'            => $maxOrder + 1,
            ]);

            DB::commit();

            return Message::created($request, "Feature created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create feature: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['feature'] = Feature::findOrFail($id);
        return view('app.backend.pages.cms.feature.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'icon'             => 'required|string|max:255',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'link_text'        => 'nullable|string|max:255',
            'link_url'         => 'nullable|url|max:255',
            'background_style' => 'nullable|string|max:255',
            'is_active'        => 'nullable|in:0,1,true,false',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $feature = Feature::findOrFail($id);
            $status = $request->input('is_active') == '1' ? 1 : 0;

            $feature->update([
                'icon'             => $request->icon,
                'title'            => $request->title,
                'description'      => $request->description,
                'link_text'        => $request->link_text,
                'link_url'         => $request->link_url,
                'background_style' => $request->background_style ?? 'bg-light',
                'is_active'        => $status,
            ]);

            DB::commit();

            return Message::updated($request, "Feature updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update feature: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->delete();

            $features = Feature::orderBy('order')->get();
            foreach ($features as $index => $feat) {
                $feat->update(['order' => $index + 1]);
            }

            return Message::deleted($request, "Feature deleted successfully", [], route('cms.feature.index'));
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to delete feature: " . $e->getMessage());
        }
    }

    public function updateOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->orders as $id => $order) {
                Feature::where('id', $id)->update(['order' => $order]);
            }

            DB::commit();
            return Message::success($request, "Order updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update order: " . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $feature = Feature::findOrFail($id);
            $feature->update(['is_active' => !$feature->is_active]);

            return Message::success($request, "Status updated successfully", [
                'is_active' => $feature->is_active,
            ]);
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to toggle status: " . $e->getMessage());
        }
    }
}
