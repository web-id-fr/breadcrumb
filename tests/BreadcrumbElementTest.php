<?php

namespace WebId\Breadcrumb\Tests;

use Illuminate\Validation\ValidationException;
use WebId\Breadcrumb\BreadcrumbElement;

class BreadcrumbElementTest extends TestCase
{
    /**
     * @dataProvider correctArrayDataProvider
     */
    public function test_it_can_make_itself_from_valid_array(array $array): void
    {
        $breadcrumbElement = BreadcrumbElement::make($array);
        $this->assertInstanceOf(BreadcrumbElement::class, $breadcrumbElement);
    }

    /**
     * @dataProvider incorrectArrayDataProvider
     */
    public function test_a_validation_exception_is_thrown_if_incorrect_array_is_provided_as_parameter(array $array): void
    {
        $this->expectException(ValidationException::class);
        BreadcrumbElement::make($array);
    }

    public function test_it_can_convert_into_array(): void
    {
        $breadcrumbArray = [
            'url' => 'https://www.url.com',
            'title' => 'title',
            'another_key' => 'something',
        ];

        $breadcrumbElement = BreadcrumbElement::make($breadcrumbArray);

        $expected = ['url' => 'https://www.url.com', 'title' => 'title'];
        $actual = $breadcrumbElement->toArray();

        $this->assertEquals($expected, $actual);
    }

    public static function incorrectArrayDataProvider(): array
    {
        return [
            [[]],
            [['foo' => 'bar', 'hello' => 'world']],
            [['url' => 'https://www.url.com', 'foo' => 'bar']],
            [['foo' => 'bar', 'title' => 'title']],
            [['https://www.url.com', 'title']],
            [['title' => 123, 'url' => 'not-an-url', 'another_key' => 'something']],
        ];
    }

    public static function correctArrayDataProvider(): array
    {
        return [
            [['url' => 'https://www.url.com', 'title' => 'title']],
            [['title' => 'title', 'url' => 'https://www.url.com']],
            [['title' => 'title', 'url' => 'https://www.url.com', 'another_key' => 'something']],
            [['title' => '', 'url' => 'http://www.url.com', 'another_key' => 'something']],
        ];
    }
}
