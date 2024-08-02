<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Schema;

class ImportController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return (new static)->getMiddleware('import');
    }

    public function tableInfo(Request $request): JsonResponse
    {
        $request->validate([
            'table' => 'required|string',
        ]);

        $table = $request->input('table');

        if (!Schema::hasTable($table)) {
            return Response::error('Table not found', 404);
        }

        $columns = array_filter(Schema::getColumns($table), function ($column) {
            return !in_array($column['name'], ['id', 'created_at', 'updated_at']);
        });

        $response = [
            'table' => $table,
            'columns' => array_values($columns)
        ];

        return Response::success('Table info retrieved successfully', $response);
    }

    public function import(Request $request): JsonResponse
    {
        // Import data

        return Response::success('Data imported successfully');
    }
}
