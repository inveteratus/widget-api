<?php

namespace Tests\Feature\Http\Controllers\WidgetController;

use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testCannotUpdateWithInvalidId(): void
    {
        $this->patch(route('widget.update', '01fdw451ch10nge8b0gkjx553q'), [])
            ->assertNotFound();
    }

    public function testCanUpdateWithValidId(): void
    {
        $widget = Widget::factory()->create(['cost' => 100]);

        $this->patchJson(route('widget.update', $widget->id), ['cost' => 999])
            ->assertNoContent();

        $this->assertDatabaseHas(Widget::class, ['id' => $widget->id, 'cost' => 999]);
    }

    public function testCannotUpdateWithZeroCost(): void
    {
        $widget = Widget::factory()->create(['cost' => 100]);

        $this->patchJson(route('widget.update', $widget->id), ['cost' => 0])
            ->assertBadRequest()
            ->assertExactJson(['cost' => ['The cost field must be at least 1.']]);
    }

    public function testCannotUpdateWithNegativeCost(): void
    {
        $widget = Widget::factory()->create(['cost' => 100]);

        $this->patchJson(route('widget.update', $widget->id), ['cost' => -1])
            ->assertBadRequest()
            ->assertExactJson(['cost' => ['The cost field must be at least 1.']]);
    }

    public function testCannotUpdateWithExistingName(): void
    {
        $widget1 = Widget::factory()->create();
        $widget2 = Widget::factory()->create();

        $this->patchJson(route('widget.update', $widget2->id), ['name' => $widget1->name])
            ->assertBadRequest()
            ->assertExactJson(['name' => ['The name has already been taken.']]);
    }

    public function testCannotUpdateWithBlankName(): void
    {
        $widget = Widget::factory()->create();

        $this->patchJson(route('widget.update', $widget->id), ['name' => ''])
            ->assertBadRequest()
            ->assertExactJson(['name' => ['The name field must be a string.']]);
    }
}
