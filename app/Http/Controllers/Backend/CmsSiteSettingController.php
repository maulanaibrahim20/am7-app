<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class CmsSiteSettingController extends Controller
{
    public function index()
    {
        $siteSetting = SiteSetting::first();

        if (!$siteSetting) {
            $siteSetting = new SiteSetting();
        }

        return view('app.backend.pages.cms.site-setting.index', compact('siteSetting'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'address' => 'nullable|string|max:255',
            'open_hours' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $siteSetting = SiteSetting::findOrFail($id);

            if ($siteSetting) {
                $siteSetting->update($request->all());
            } else {
                $siteSetting = SiteSetting::create($request->all());
            }

            DB::commit();
            return Message::created($request, "Site Setting update successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to create Site Setting");
        }
    }
}
