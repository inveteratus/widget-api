<?php

namespace Tests\Feature\Http\Controllers\WidgetController;

use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function testCannotDeleteWithInvalidId(): void
    {
        $this->delete(route('widget.destroy', '01fdw451ch10nge8b0gkjx553q'))
            ->assertNotFound();
    }

    public function testCanDeleteWithValidId(): void
    {
        $widget = Widget::factory()->create();

        $this->delete(route('widget.destroy', $widget->id))
            ->assertNoContent();

        $this->assertDatabaseMissing(Widget::class, ['id' => $widget->id]);
    }
}
