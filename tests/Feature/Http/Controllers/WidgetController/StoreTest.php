<?php

namespace Tests\Feature\Http\Controllers\WidgetController;

use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testCanStoreWithValidDetails(): void
    {
        $response = $this->postJson(route('widget.store'), ['name' => 'Test', 'cost' => 100]);
        $response->assertCreated()
            ->assertJsonStructure(['id']);

        $content = $response->decodeResponseJson();

        $this->assertDatabaseHas(Widget::class, ['id' => $content['id']]);
    }

    public function testCannotStoreWithZeroCost(): void
    {
        $this->postJson(route('widget.store'), ['name' => 'Test', 'cost' => 0])
            ->assertBadRequest()
            ->assertExactJson(['cost' => ['The cost field must be at least 1.']]);
    }

    public function testCannotStoreWithNegativeCost(): void
    {
        $this->postJson(route('widget.store'), ['name' => 'Test', 'cost' => -1])
            ->assertBadRequest()
            ->assertExactJson(['cost' => ['The cost field must be at least 1.']]);
    }

    public function testCannotStoreWithExistingName(): void
    {
        $widget = Widget::factory()->create();

        $this->postJson(route('widget.store'), ['name' => $widget->name, 'cost' => 100])
            ->assertBadRequest()
            ->assertExactJson(['name' => ['The name has already been taken.']]);
    }

    public function testCannotStoreWithBlankName(): void
    {
        $this->postJson(route('widget.store'), ['name' => '', 'cost' => 100])
            ->assertBadRequest()
            ->assertExactJson(['name' => ['The name field is required.']]);
    }

    public function testCannotStoreWithMissingName(): void
    {
        $this->postJson(route('widget.store'), ['cost' => 100])
            ->assertBadRequest()
            ->assertExactJson(['name' => ['The name field is required.']]);
    }

    public function testCannotStoreWithMissingCost(): void
    {
        $this->postJson(route('widget.store'), ['name' => 'Test'])
            ->assertBadRequest()
            ->assertExactJson(['cost' => ['The cost field is required.']]);
    }
}
