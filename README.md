# Multiformat Endpoints in Laravel

[![Latest Stable Version](https://poser.pugx.org/ptournet/laravel-multiformat/v)](//packagist.org/packages/ptournet/laravel-multiformat) 
[![Latest Unstable Version](https://poser.pugx.org/ptournet/laravel-multiformat/v/unstable)](//packagist.org/packages/ptournet/laravel-multiformat) 
[![Build Status](https://travis-ci.com/ptournet/laravel-multiformat.svg?branch=master)](https://travis-ci.com/ptournet/laravel-multiformat) 
[![Total Downloads](https://poser.pugx.org/ptournet/laravel-multiformat/downloads)](//packagist.org/packages/ptournet/laravel-multiformat) 
[![License](https://poser.pugx.org/ptournet/laravel-multiformat/license)](//packagist.org/packages/ptournet/laravel-multiformat) 

This package allows a single Laravel route to answer with different formats (often HTML and JSON). It is meant to be a drop-in replacement fo the unmaintained package: [m1guelpf/laravel-multiformat](https://github.com/m1guelpf/laravel-multiformat) with new features and compatibility with the latest Laravel versions.   

## Installation

You can install the package via composer:

```bash
composer require ptournet/laravel-multiformat
```

## Usage

``` php
<?php

/**
 * Mark a route as 'multiformat' to allow different extensions (html, json, xml, etc.)
 *
 * This route will match all of these requests:
 *     /podcasts/4
 *     /podcasts/4.json
 *     /podcasts/4.html
 *     /podcasts/4.zip
 */
Route::get('/podcasts/{id}', 'PodcastsController@show')->multiformat();

/**
 * Use `Request::match()` to return the right response for the requested format.
 *
 * Supports closures to avoid doing unnecessary work, and returns 404 if the
 * requested format is not supported.
 *
 * Will also take into account the `Accept` header if no extension is provided.
 */
class PodcastsController
{
    public function show($id)
    {
        $podcast = Podcast::findOrFail($id);
        
        return request()->match([
            'html' => view('podcasts.show', [
                'podcast' => $podcast,
                'episodes' => $podcast->recentEpisodes(5),
            ]),
            'json' => $podcast,
            'xml' => function () use ($podcast) {
                return response($podcast->toXml(), 200, ['Content-Type' => 'text/xml']);
            }
        ]);
    }
}
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email `ptournet (at sign) gmail.com` instead of using the issue tracker.

## Credits

- [Adam Wathan](https://github.com/adamwathan) for the [original gist](https://gist.github.com/adamwathan/984914b2eee8e4d79a06f7045e4ce999)
- [Miguel Piedrafita](https://github.com/m1guelpf) for the [original plugin](https://github.com/m1guelpf/laravel-multiformat)
- [Stefan Bauer](https://github.com/stefanbauer) who contributed to the [original plugin](https://github.com/m1guelpf/laravel-multiformat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
