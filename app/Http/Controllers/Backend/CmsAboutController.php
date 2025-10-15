<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage, Validator};
use Illuminate\Support\Str;
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\{AboutFeature, AboutSection};
use Yajra\DataTables\DataTables;

class CmsAboutController extends Controller
{
    public function index()
    {
        $aboutSection = AboutSection::with('features')->first();
        $canAddMore = AboutFeature::count() < 3;

        if (!$aboutSection) {
            $aboutSection = new AboutSection();
        }

        return view('app.backend.pages.cms.about.index', compact('aboutSection', 'canAddMore'));
    }

    public function getData(Request $request)
    {
        $rows = AboutFeature::select('*')->orderBy('order', 'asc');

        return DataTables::of($rows)
            ->addIndexColumn()

            ->editColumn('description', function ($row) {
                return Str::limit($row->description, 60);
            })

            ->addColumn('action', function ($row) {
                $editUrl = route('cms.about.editFeature', $row->id);
                $deleteUrl = route('cms.about.destroyFeature', $row->id);

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

            ->rawColumns(['icon', 'action'])
            ->make();
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'subtitle'         => 'nullable|string|max:255',
            'title'            => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'experience_years' => 'nullable|integer|min:0',
            'experience_label' => 'nullable|string|max:255',
            'button_text'      => 'nullable|string|max:255',
            'button_url'       => 'nullable|url|max:255',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $aboutSection = AboutSection::first();
            $data = $request->except('image');

            if ($request->hasFile('image')) {
                if ($aboutSection && $aboutSection->image) {
                    Storage::disk('public')->delete($aboutSection->image);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('about', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            if ($aboutSection) {
                $aboutSection->update($data);
            } else {
                $aboutSection = AboutSection::create($data);
            }

            DB::commit();
            return Message::success($request, "About section updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to update about section: " . $e->getMessage());
        }
    }

    public function createFeature()
    {
        return view('app.backend.pages.cms.about.create');
    }

    public function storeFeature(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $aboutSection = AboutSection::firstOrCreate([]);

            if ($aboutSection->features->count() >= 3) {
                return Message::error($request, "Maximum 3 features allowed!");
            }

            $maxOrder = $aboutSection->features()->max('order') ?? 0;

            $aboutSection->features()->create([
                'title'       => $request->title,
                'description' => $request->description,
                'order'       => $maxOrder + 1,
            ]);

            DB::commit();

            return Message::created($request, "Feature created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to create feature: " . $e->getMessage());
        }
    }
    public function editFeature($id)
    {
        $data['feature'] = AboutFeature::findOrFail($id);

        return view('app.backend.pages.cms.about.edit', $data);
    }

    public function updateFeature(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $feature = AboutFeature::findOrFail($id);

            $feature->update([
                'title'       => $request->title,
                'description' => $request->description,
            ]);

            DB::commit();

            return Message::updated($request, "Feature updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to update feature: " . $e->getMessage());
        }
    }

    public function destroyFeature(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $feature = AboutFeature::findOrFail($id);
            $aboutSectionId = $feature->about_section_id;

            $feature->delete();

            $features = AboutFeature::where('about_section_id', $aboutSectionId)
                ->orderBy('order')
                ->get();

            foreach ($features as $index => $feat) {
                $feat->update(['order' => $index + 1]);
            }

            DB::commit();

            return Message::deleted($request, "About feature deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to delete feature: " . $e->getMessage());
        }
    }

    public function updateFeatureOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $orders = $request->orders;

            foreach ($orders as $id => $order) {
                AboutFeature::where('id', $id)->update(['order' => $order]);
            }

            DB::commit();

            return Message::updated($request, "Feature order updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to update feature order: " . $e->getMessage());
        }
    }
}
