<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\Carousel;

class CmsCarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::orderBy('order', 'asc')->get();

        $shouldFixOrder = false;
        $expectedOrder = 1;

        foreach ($carousels as $carousel) {
            if ($carousel->order !== $expectedOrder) {
                $shouldFixOrder = true;
                break;
            }
            $expectedOrder++;
        }

        if ($shouldFixOrder) {
            foreach ($carousels as $index => $carousel) {
                $carousel->update(['order' => $index + 1]);
            }
        }

        $carousels = Carousel::orderBy('order', 'asc')
            ->take(5)
            ->get();

        $canAddMore = $carousels->count() < 5;

        return view('app.backend.pages.cms.carousel.index', [
            'carousels' => $carousels,
            'canAddMore' => $canAddMore
        ]);
    }

    public function create()
    {
        return view('app.backend.pages.cms.carousel.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);

        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'subtitle'          => 'nullable|string',
            'button_text'       => 'nullable|string|max:100',
            'button_url'        => 'nullable|url|max:255',
            'background_image'  => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foreground_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order'             => 'nullable|integer|min:1',
            'is_active'         => 'boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {

            $totalCarousel = Carousel::count();
            if ($totalCarousel >= 5) {
                return Message::error($request, "Maksimal hanya boleh 5 carousel aktif di sistem.");
            }

            $bgPath = $request->file('background_image')
                ->store('landing/img/carousel', 'public');

            $fgPath = $request->hasFile('foreground_image')
                ? $request->file('foreground_image')->store('uploads/carousels', 'public')
                : null;

            $inputOrder = $request->order ?? 1;
            $maxOrder   = Carousel::max('order') ?? 0;

            if ($inputOrder > $maxOrder + 1) {
                $inputOrder = $maxOrder + 1;
            }

            Carousel::where('order', '>=', $inputOrder)
                ->increment('order');

            Carousel::create([
                'title'            => $request->title,
                'subtitle'         => $request->subtitle,
                'button_text'      => $request->button_text,
                'button_url'       => $request->button_url,
                'background_image' => $bgPath,
                'foreground_image' => $fgPath,
                'order'            => $inputOrder,
                'is_active'        => $request->is_active ? 1 : 0,
            ]);

            DB::commit();
            return Message::created($request, "Carousel created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to create carousel");
        }
    }

    public function show($id)
    {
        $data['carousel'] = Carousel::findOrFail($id);
        return view('app.backend.pages.cms.carousel.show', $data);
    }

    public function preview()
    {
        $carousels = Carousel::where('is_active', true)
            ->orderBy('order', 'asc')
            ->take(5)
            ->get();

        return view('app.backend.pages.cms.carousel.preview', compact('carousels'));
    }


    public function edit($id)
    {
        $carousel = Carousel::findOrFail($id);
        return view('app.backend.pages.cms.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $carousel = Carousel::findOrFail($id);

        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);

        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'subtitle'          => 'nullable|string',
            'button_text'       => 'nullable|string|max:100',
            'button_url'        => 'nullable|url|max:255',
            'background_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'foreground_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order'             => 'nullable|integer|min:1',
            'is_active'         => 'boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $totalCarousel = Carousel::where('id', '!=', $carousel->id)->count();
            if ($totalCarousel >= 5 && $request->is_active && !$carousel->is_active) {
                return Message::error($request, "Maksimal hanya boleh 5 carousel aktif di sistem.");
            }

            $bgPath = $carousel->background_image;
            $fgPath = $carousel->foreground_image;

            if ($request->hasFile('background_image')) {
                if ($bgPath && Storage::disk('public')->exists($bgPath)) {
                    Storage::disk('public')->delete($bgPath);
                }
                $bgPath = $request->file('background_image')
                    ->store('landing/img/carousel', 'public');
            }

            if ($request->hasFile('foreground_image')) {
                if ($fgPath && Storage::disk('public')->exists($fgPath)) {
                    Storage::disk('public')->delete($fgPath);
                }
                $fgPath = $request->file('foreground_image')
                    ->store('uploads/carousels', 'public');
            }

            $inputOrder = $request->order ?? $carousel->order;
            $maxOrder   = Carousel::max('order') ?? 0;

            if ($inputOrder > $maxOrder + 1) {
                $inputOrder = $maxOrder + 1;
            }

            if ($inputOrder != $carousel->order) {
                Carousel::where('order', '>=', $inputOrder)
                    ->where('id', '!=', $carousel->id)
                    ->increment('order');
            }

            $carousel->update([
                'title'            => $request->title,
                'subtitle'         => $request->subtitle,
                'button_text'      => $request->button_text,
                'button_url'       => $request->button_url,
                'background_image' => $bgPath,
                'foreground_image' => $fgPath,
                'order'            => $inputOrder,
                'is_active'        => $request->is_active ? 1 : 0,
            ]);

            DB::commit();
            return Message::updated($request, "Carousel updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to update carousel");
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = Carousel::findOrFail($id);
            $category->delete();

            return Message::deleted($request, "Carousel deleted successfully");
        } catch (\Exception $e) {
            return Message::exception($request, $e, "Failed to update carousel: " . $e->getMessage());
        }
    }
}
