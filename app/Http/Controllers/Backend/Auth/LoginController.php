<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Facades\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Validator};
use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:3|max:50',
            'password' => 'required|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return Message::validator($request, $validator->errors());
        }

        DB::beginTransaction();

        try {

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return Message::unauthorized($request, "Incorrect email address/username or password.");
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();

                DB::commit();
                return redirect(route('dashboard'));
            } else {
                DB::rollBack();
                return Message::unauthorized($request, "Incorrect email address/username or password.");
            }

            DB::rollBack();
            return Message::unauthorized($request, "Incorrect email/username or password.");
        } catch (\Exception $e) {
            DB::rollBack();
            return Message::error($request, "An error has occurred " . $e->getMessage());
        }
    }
}
