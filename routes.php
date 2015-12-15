<?php


App::before(function($request) {

    // route for beta
    if (Config::get('app.beta') && !Session::get('genius.beta.unlocked') && !BackendAuth::getUser()) {

        $allowed = [
            'beta',
            trim(Config::get('app.beta'), ' /'),
            trim(Config::get('cms.backendUri'), ' /'),
        ];

        Route::any('{any}', function ($any) {

            return Redirect::to(Config::get('app.beta'));

        })->where('any', '^(?!(' . implode('|',$allowed). ')).*');
    }

    Route::any('beta', function () {

        Session::put('genius.beta.unlocked', true);
        return Redirect::to('');
    });
});