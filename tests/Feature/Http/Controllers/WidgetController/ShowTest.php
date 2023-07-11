<?php

namespace Tests\Feature\Http\Controllers\WidgetController;

use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function testCannotShowAnInvalidWidget(): void
    {
        $this->get(route('widget.show', '01fdw451ch10nge8b0gkjx553q'))
            ->assertNotFound();
    }

    public function testCanShowExistingWidget(): void
    {
        $widget = Widget::factory()->create();

        $this->get(route('widget.show', $widget->id))
            ->assertSuccessful()
            ->assertExactJson([
                'id' => $widget->id,
                'name' => $widget->name,
                'cost' => $widget->cost,
                'in_stock' => $widget->in_stock,
                'created' => $widget->created_at->toIso8601ZuluString(),
            ]);
    }
}
