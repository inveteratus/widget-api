<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

class WidgetCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Tweak the pagination information slightly.
     *
     * @param  array<string, mixed>  $paginated
     * @param  array<string, mixed>  $default
     * @return array<string, mixed>
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            // Change the links.prev key to links.previous
            'links' => Arr::except($default['links'], 'prev') + [
                'previous' => $default['links']['prev'],
            ],

            // Remove the meta.links array and change the links.per_page key to links.items_per_page
            'meta' => Arr::except($default['meta'], ['links', 'per_page']) + [
                'items_per_page' => $default['meta']['per_page'],
            ],
        ];
    }
}
