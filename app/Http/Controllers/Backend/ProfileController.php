<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Storage, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $data['user'] = User::where('id', Auth::id())->first();

        return view('app.backend.pages.profile.index', $data);
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone'  => 'nullable|string|max:20',
            // 'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800', // 800 KB
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $user = Auth::user();

            if (!$user) {
                return Message::error($request, "User not found");
            }
            $user = User::find($user->id);

            // if ($request->hasFile('avatar')) {
            //     $file     = $request->file('avatar');
            //     $filename = 'avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            //     $path     = $file->storeAs('avatars', $filename, 'public');

            //     // Optional: hapus avatar lama kalau ada
            //     if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            //         Storage::disk('public')->delete($user->avatar);
            //     }

            //     $user->avatar = $path;
            // }

            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                // 'avatar' => $user->avatar
            ]);

            DB::commit();

            return Message::updated($request, "Profile updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to update profile: " . $e->getMessage());
        }
    }

    public function deleteAccount(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'confirm_delete' => 'accepted'
        ], [
            'confirm_delete.accepted' => 'You must confirm the account deactivation.'
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $user = Auth::user();

            $user = User::find($user->id);

            // Optional: hapus avatar jika ada
            // if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            //     Storage::disk('public')->delete($user->avatar);
            // }

            Auth::logout();

            $user->delete();

            DB::commit();

            return Message::deleted($request, "Your account has been deactivated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to delete account: " . $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => [
                'required',
                'string',
                'min:8',
            ],
            'confirm_password' => 'required|same:new_password'
        ], [
            'new_password.regex' => 'New password must contain at least one uppercase letter and one special symbol.',
            'confirm_password.same' => 'Password confirmation does not match.'
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $user = Auth::user();

            $user = User::find($user->id);

            if (!Hash::check($request->current_password, $user->password)) {
                return Message::error($request, "Current password is incorrect.");
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            DB::commit();

            return Message::updated($request, "Password changed successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::exception($request, $e, "Failed to change password: " . $e->getMessage());
        }
    }
}
