<?php

namespace Ptournet\Multiformat;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class MultiformatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Route::macro('multiformat', function () {
            return $this->setUri($this->uri().'{_format?}');
        });

        Request::macro('matchFormat', function ($responses, $defaultFormat = 'html') {
            if ($this->route('_format') === null) {
                return value(Arr::get($responses, $this->format($defaultFormat)));
            }

            return value(Arr::get($responses, Str::after($this->route('_format'), '.'), function () {
                abort(404);
            }));
        });

        Request::macro('match', function ($responses, $defaultFormat = 'html') {
            echo "match() macro has been deprecated and will be removed in v2, please use matchFormat() instead.\n";

            return $this->matchFormat($responses, $defaultFormat);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
