<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Direksi;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DireksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $direksi = Direksi::all();
            return response()->json($direksi, 200, [], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $direksi = Direksi::findOrFail($id);
            return response()->json($direksi, 200, [], JSON_PRETTY_PRINT);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Direksi not found',
                'message' => "No direksi found with ID {$id}"
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
