<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class SettingController extends Controller
{
    public function index()
    {
        return view('app.backend.pages.setting.endpoint-cront-taks.index');
    }

    public function getData(Request $request)
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'cron');
        })->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => '/' . $route->uri(),
                'name' => $route->getName() ?? '-',
                'action' => $route->getActionName(),
            ];
        });

        return DataTables::of($routes)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-secondary" target="_blank" disabled>Run</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
