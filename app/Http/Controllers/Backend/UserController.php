<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Validator};
use App\Facades\Message;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user = new User();
    }
    public function index()
    {
        return view('app.backend.pages.user.index');
    }

    public function getData(Request $request)
    {
        $rows = $this->user->select(['*'])->where('id', '!=', Auth::id())->orderBy('id', 'desc');

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $url = route('user.show', $row->id);
                return '<a href="' . $url . '" class="text-primary text-decoration-none" data-toggle="ajaxModal"
                data-title="Detail | User">' . $row->name . '</a>';
            })
            ->editColumn('is_active', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('is_verified', function ($row) {
                return $row->email_verified_at
                    ? '<span class="badge bg-success">Verified</span>'
                    : '<span class="badge bg-danger">Not Verified</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('user.edit', $row->id);

                return '
                <a href="' . $editUrl . '" class="btn btn-warning" data-toggle="ajaxModal" data-title="User | Edit">
                    <i class="fas fa-pencil me-1"></i>
                </a>

            ';
            })
            ->rawColumns(['is_active', 'is_verified', 'action', 'name'])
            ->make();
    }

    public function create()
    {
        $user = Auth::user();
        $query = Role::where('guard_name', 'web');

        if (!$user->hasRole('admin')) {
            $query->where('name', '!=', 'admin');
        }

        $data['role'] = $query->get();
        return view('app.backend.pages.user.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6',
            'phone'             => 'nullable|string|max:20',
            'is_active'         => 'nullable|boolean',
            'email_verified'    => 'nullable|boolean',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $status = $request->has('is_active') ? 1 : 0;
            $verifiedAt = $request->has('email_verified') ? now() : null;

            $user = $this->user->create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'phone'             => $request->phone,
                'is_active'         => $status,
                'email_verified_at' => $verifiedAt,
            ]);

            $user->assignRole($request->role_id);

            DB::commit();

            return Message::created($request, "User created successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to create user" . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $data['user'] = User::where('id', $id)->firstOrFail();

        return view('app.backend.pages.user.show', $data);
    }

    public function edit($id)
    {
        $data['user'] = $this->user->where('id', $id)->firstOrFail();
        $data['role'] = Role::where('guard_name', 'web')->get();

        return view('app.backend.pages.user.edit', $data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $id,
            'password'          => 'nullable|string|min:6',
            'phone'             => 'nullable|string|max:20',
            'is_active'         => 'nullable|boolean',
            'email_verified'    => 'nullable|boolean',
            'role_id'           => 'required',
        ]);

        if (!$validator->passes()) {
            return Message::validator($request, $validator->errors()->first());
        }

        try {
            $user = $this->user->findOrFail($id);

            $status = $request->has('is_active') ? 1 : 0;
            $verifiedAt = $request->has('email_verified') ? now() : null;

            $dataUpdate = [
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'is_active'         => $status,
                'email_verified_at' => $verifiedAt,
            ];

            if ($request->filled('password')) {
                $dataUpdate['password'] = Hash::make($request->password);
            }

            $user->update($dataUpdate);

            $user->syncRoles([$request->role_id]);

            DB::commit();

            return Message::updated($request, "User updated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            return Message::exception($request, $e, "Failed to update user" . $e->getMessage());
        }
    }
}
