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
            'login' => 'required|min:3|max:50',
            'password' => 'required|min:6|max:50',
        ]);

        if ($validator->fails()) {
            return Message::validator($request, $validator->errors());
        }

        DB::beginTransaction();

        try {
            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $user = User::where($loginField, $request->login)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return Message::unauthorized($request, "Incorrect email address/username or password.");
            }

            if (Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
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
