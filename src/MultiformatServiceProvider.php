<?php

namespace Ptournet\Multiformat;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

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

        Request::macro('match', function ($responses, $defaultFormat = 'html') {
            echo "Macro 'match' has been marked as deprecated\n";

            if ($this->route('_format') === null) {
                return value(array_get($responses, $this->format($defaultFormat)));
            }

            return value(array_get($responses, str_after($this->route('_format'), '.'), function () {
                abort(404);
            }));
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
