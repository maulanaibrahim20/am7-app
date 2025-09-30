<?php

namespace App\Facades;

use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\Log;

class Message
{
    public static function success(
        Request $request,
        string $message = "Operation successful",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => [$message],
                'data' => $data
            ]);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('msg', $message);
    }

    public static function error(
        Request $request,
        string $message = "Operation failed",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'data' => $data
            ]);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('error', $message);
    }

    public static function created(
        Request $request,
        string $message = "Data has been created successfully",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => [$message],
                'data' => $data
            ], 201);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('msg', $message);
    }

    public static function updated(
        Request $request,
        string $message = "Data has been updated successfully",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        return self::success($request, $message, $data, $redirectUrl);
    }

    public static function deleted(
        Request $request,
        string $message = "Data has been deleted successfully",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        return self::success($request, $message, $data, $redirectUrl);
    }

    public static function notFound(
        Request $request,
        string $message = "Data not found",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'data' => $data
            ], 404);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('error', $message);
    }

    public static function unauthorized(
        Request $request,
        string $message = "Unauthorized access",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'data' => $data
            ], 401);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('error', $message);
    }

    public static function forbidden(
        Request $request,
        string $message = "Access forbidden",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'data' => $data
            ], 403);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('error', $message);
    }

    public static function validator(
        Request $request,
        $message = "Validation failed",
        array $errors = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'errors' => $errors
            ], 422);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->withErrors($errors)->with('error', $message);
    }

    public static function withData(
        Request $request,
        string $message = "Data retrieved successfully",
        array $data = [],
        ?string $redirectUrl = null
    ) {
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => [$message],
                'data' => $data
            ]);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('msg', $message)->with('data', $data);
    }

    public static function exception(
        Request $request,
        \Exception $exception,
        string $message = "An error occurred",
        ?string $redirectUrl = null
    ) {
        // Log the exception
        Log::error($exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => [$message],
                'error' => config('app.debug') ? $exception->getMessage() : null
            ], 500);
        }

        $redirect = $redirectUrl ? redirect($redirectUrl) : redirect()->back();
        return $redirect->with('error', $message);
    }

    public static function customAjax(
        array $data,
        int $statusCode = 200
    ): JsonResponse {
        return response()->json($data, $statusCode);
    }

    public static function customRedirect(
        string $redirectUrl,
        string $message = "",
        string $type = 'msg'
    ): RedirectResponse {
        return redirect($redirectUrl)->with($type, $message);
    }
}
