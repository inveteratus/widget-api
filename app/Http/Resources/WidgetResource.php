<?php

namespace App\Http\Resources;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * @property string $id
 * @property string $name
 * @property int $cost
 * @property bool $in_stock
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class WidgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Remove the updated_at field and replace the created_at field with created
        return Arr::except(parent::toArray($request), ['created_at', 'updated_at']) + [
            'created' => $this->created_at->toIso8601ZuluString(),
        ];
    }
}
