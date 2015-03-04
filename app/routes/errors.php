<?php

App::error(function(Exception $exception, $code)
{

    // Log::error($exception);

    // if (Config::get('app.debug')) {
    //     return;
    // }

    switch ($code)
    {
        case 403: 
        case 404:            
        case 500:  
            // return App::make('FrontController')->callAction('showError', array($code));            
            // return Response::view('errors.'.$code, array(), $code);
            return \View::make('errors.'.$code, ['code' => $code]);
            break;

        default:
            return \View::make('errors.default');
            // return Response::view('errors.default', array(), $code);
            break;
    }
});
