<?php

namespace Tests\Unit;

use App\Http\Controllers\HomeController;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_normalize_category_summary_maps_category_data_to_homepage_shape(): void
    {
        $controller = new HomeController();

        $category = new \stdClass();
        $category->CATEGORY_ID = 7;
        $category->CATEGORY_NAME = 'Bangla Literature';
        $category->ICON = '📚';
        $category->DISPLAY_ORDER = 7;
        $category->total_books = 3;

        $result = $this->invokeProtectedMethod($controller, 'normalizeCategorySummary', [collect([$category])]);

        $this->assertCount(1, $result);
        $this->assertSame(7, $result->first()->category_id);
        $this->assertSame('Bangla Literature', $result->first()->category_name);
        $this->assertSame('📚', $result->first()->icon);
        $this->assertSame(3, $result->first()->total_books);
    }

    protected function invokeProtectedMethod(object $object, string $method, array $args = [])
    {
        $reflection = new \ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
