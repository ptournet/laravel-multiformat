<?php

namespace Ptournet\Multiformat\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;
use Ptournet\Multiformat\MultiformatServiceProvider;

class MultiformatServiceProviderTest extends TestCase
{
    protected function getPackageProviders($application)
    {
        return [
            MultiformatServiceProvider::class
        ];
    }

    /** @test */
    public function the_multiformat_macro_should_alter_a_route_uri()
    {
        $uri = "initial/endpoint";
        $route = Route::get($uri, function () {});
        $this->assertEquals($uri, $route->uri);

        $route->multiformat();
        $this->assertEquals($uri . '{_format?}', $route->uri);
    }

    /** @test */
    public function the_match_macro_should_return_the_correct_type_for_a_non_multiformat_endpoint_based_on_the_http_accept()
    {
        $uri = "initial/endpoint";
        Route::get($uri, function (Request $request) {
            return $request->match([
                'html' => 1,
                'json' => 2,
                'xml' => 3
            ]);
        });

        $response = $this->get($uri);
        $this->assertEquals('1', $response->content());
        $response = $this->get($uri, ['Accept' => 'application/json']);
        $this->assertEquals('2', $response->content());
        $response = $this->get($uri, ['Accept' => 'application/xml']);
        $this->assertEquals('3', $response->content());
    }

    /** @test */
    public function the_match_macro_should_return_the_correct_type_for_a_valid_format_for_a_multiformat_endpoint()
    {
        $uri = "initial/endpoint";
        Route::get($uri, function (Request $request) {
            return $request->match([
                'html' => 1,
                'json' => 2,
                'xml' => 3
            ]);
        })->multiformat();

        $response = $this->get($uri);
        $this->assertEquals('1', $response->content());
        $response = $this->get($uri.'.html');
        $this->assertEquals('1', $response->content());
        $response = $this->get($uri.'.json');
        $this->assertEquals('2', $response->content());
        $response = $this->get($uri.'.xml');
        $this->assertEquals('3', $response->content());
    }

    /** @test */
    public function the_match_macro_should_return_a_404_for_an_invalid_format_for_a_multiformat_endpoint()
    {
        $uri = "initial/endpoint";
        Route::get($uri, function (Request $request) {
            return $request->match([
                'html' => 1,
                'json' => 2,
                'xml' => 3
            ]);
        })->multiformat();

        $response = $this->get($uri.'.foo');
        $response->assertStatus(404);
    }

}
