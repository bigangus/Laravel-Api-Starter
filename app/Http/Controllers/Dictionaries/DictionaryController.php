<?php

namespace App\Http\Controllers\Dictionaries;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Responses\Response;
use App\Models\System\Dictionary;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;

class DictionaryController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return (new static)->getMiddleware('dict');
    }

    public function index(): JsonResponse
    {
        return Response::success(
            'Dictionaries retrieved successfully',
            ['dictionaries' => Dictionary::all(['id', 'name', 'key', 'value'])]
        );
    }

    public function show(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer']);

        $dictionary = Dictionary::findOrFail($request->input('id'));

        return Response::success('Dictionary retrieved successfully', ['dictionary' => $dictionary]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'value' => 'required|string',
        ]);

        $dictionary = Dictionary::findOrFail($request->input('id'));

        $dictionary->update($request->only(['name', 'value']));

        return Response::success('Dictionary updated successfully', ['dictionary' => $dictionary]);
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $dictionary = Dictionary::create($request->only(['name', 'key', 'value']));

        return Response::success('Dictionary created successfully', ['dictionary' => $dictionary]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|integer']);

        $dictionary = Dictionary::findOrFail($request->input('id'));

        $dictionary->delete();

        return Response::success('Dictionary deleted successfully');
    }
}
