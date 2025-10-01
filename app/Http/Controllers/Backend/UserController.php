<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
        $rows = $this->user->select(['*']);

        return DataTables::of($rows)
            ->addIndexColumn()
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
                $editUrl = url('user.edit', $row->id);
                $showUrl = url('user.show', $row->id);

                return '
                <a href="' . $editUrl . '" class="btn btn-warning" data-toggle="ajaxModal" data-title="User | Edit">
                    <i class="fas fa-pencil me-1"></i>
                </a>
                <a href="' . $showUrl . '" class="btn btn-primary">
                    <i class="fas fa-eye me-1"></i>
                </a>
            ';
            })
            ->rawColumns(['is_active', 'is_verified', 'action'])
            ->make();
    }

    public function create()
    {
        return view('app.backend.pages.user.create');
    }
}
