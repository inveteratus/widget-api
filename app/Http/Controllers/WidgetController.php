<?php

namespace App\Http\Controllers;

use App\Http\Resources\WidgetCollection;
use App\Http\Resources\WidgetResource;
use App\Models\Widget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class WidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): WidgetCollection|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search' => 'string',
            'order' => 'string|'.Rule::in(['name', 'name:desc', 'cost', 'cost:desc', 'created', 'created:desc']),
            'page' => 'integer|min:1',
            'ipp' => 'integer|'.Rule::in([10, 25, 50, 100, 200]),
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), SymfonyResponse::HTTP_BAD_REQUEST);
        }

        $query = Widget::query();

        // Apply search
        $search = $request->get('search');
        if ($search) {
            // Ensure we escape the search string properly
            $query->orWhere('name', 'like', '%'.str_replace(['_', '%', '\\'], ['\\_', '\\%', '\\\\'], $search).'%');
        }

        // Apply order
        $order = $request->get('order', 'created:desc');
        if ($order) {
            if (($column = str($order)->before(':')->toString()) == 'created') {
                $column = 'created_at';
            }

            $query->orderBy(
                $column,
                str($order)->endsWith(':desc') ? 'desc' : 'asc'
            );
        }

        return new WidgetCollection($query->paginate($request->get('ipp', 10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make((array) $request->json()->all(), [
            'name' => 'required|string|unique:widgets',
            'cost' => 'required|integer|min:1',
            'in_stock' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), SymfonyResponse::HTTP_BAD_REQUEST);
        }

        $widget = Widget::query()->create($validator->validated());

        return response()->json(['id' => $widget->id], SymfonyResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Widget $widget): WidgetResource
    {
        return new WidgetResource($widget);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Widget $widget): Response|JsonResponse
    {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'string|'.Rule::unique(Widget::class)->ignore($widget->id),
            'cost' => 'integer|min:1',
            'in_stock' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), SymfonyResponse::HTTP_BAD_REQUEST);
        }

        if (count($validator->validated())) {
            $widget->update($validator->validated());
        }

        return response(null, SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Widget $widget): Response
    {
        $widget->query()->delete();

        return response(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
