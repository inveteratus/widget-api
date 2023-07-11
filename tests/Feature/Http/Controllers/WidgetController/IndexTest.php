<?php

namespace Tests\Feature\Http\Controllers\WidgetController;

use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testCanIndexWithNoWidgets(): void
    {
        $this->get(route('widget.index'))
            ->assertSuccessful()
            ->AssertExactJson([
                'data' => [],
                'links' => [
                    'first' => config('app.url').'/widget?page=1',
                    'previous' => null,
                    'next' => null,
                    'last' => config('app.url').'/widget?page=1',
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => null,
                    'items_per_page' => 10,
                    'last_page' => 1,
                    'path' => config('app.url').'/widget',
                    'to' => null,
                    'total' => 0,
                ],
            ]);
    }

    public function testCanIndexWithWidgets(): void
    {
        $w1 = Widget::factory()->create(['name' => 'Tom', 'cost' => 100, 'created_at' => now()->subSeconds(30)]);
        $w2 = Widget::factory()->create(['name' => 'Dick', 'cost' => 200, 'created_at' => now()->subSeconds(20)]);
        $w3 = Widget::factory()->create(['name' => 'Harry', 'cost' => 300]);

        $this->get(route('widget.index'))
            ->assertSuccessful()
            ->AssertExactJson([
                'data' => [
                    [
                        'id' => $w3->id,
                        'name' => $w3->name,
                        'cost' => $w3->cost,
                        'in_stock' => $w3->in_stock,
                        'created' => $w3->created_at->toIso8601ZuluString(),
                    ],
                    [
                        'id' => $w2->id,
                        'name' => $w2->name,
                        'cost' => $w2->cost,
                        'in_stock' => $w2->in_stock,
                        'created' => $w2->created_at->toIso8601ZuluString(),
                    ],
                    [
                        'id' => $w1->id,
                        'name' => $w1->name,
                        'cost' => $w1->cost,
                        'in_stock' => $w1->in_stock,
                        'created' => $w1->created_at->toIso8601ZuluString(),
                    ],
                ],
                'links' => [
                    'first' => config('app.url').'/widget?page=1',
                    'previous' => null,
                    'next' => null,
                    'last' => config('app.url').'/widget?page=1',
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'items_per_page' => 10,
                    'last_page' => 1,
                    'path' => config('app.url').'/widget',
                    'to' => 3,
                    'total' => 3,
                ],
            ]);
    }

    public function testCanIndexWithSearch(): void
    {
        $w1 = Widget::factory()->create(['name' => 'Tom', 'cost' => 100, 'created_at' => now()->subSeconds(30)]);
        $w2 = Widget::factory()->create(['name' => 'Dick', 'cost' => 200, 'created_at' => now()->subSeconds(20)]);
        $w3 = Widget::factory()->create(['name' => 'Harry', 'cost' => 300]);

        $this->get(route('widget.index').'?search=i')
            ->assertSuccessful()
            ->AssertExactJson([
                'data' => [
                    [
                        'id' => $w2->id,
                        'name' => $w2->name,
                        'cost' => $w2->cost,
                        'in_stock' => $w2->in_stock,
                        'created' => $w2->created_at->toIso8601ZuluString(),
                    ],
                ],
                'links' => [
                    'first' => config('app.url').'/widget?page=1',
                    'previous' => null,
                    'next' => null,
                    'last' => config('app.url').'/widget?page=1',
                ],
                'meta' => [
                    'current_page' => 1,
                    'from' => 1,
                    'items_per_page' => 10,
                    'last_page' => 1,
                    'path' => config('app.url').'/widget',
                    'to' => 1,
                    'total' => 1,
                ],
            ]);
    }

    public function testCannotIndexWithAnInvalidIpp(): void
    {
        $this->get(route('widget.index').'?ipp=1')
            ->assertBadRequest()
            ->AssertExactJson([
                'ipp' => ['The selected ipp is invalid.'],
            ]);
    }
}
