<?php
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
// Exception: MethodNotAllowedHttpException
App::error(function(MethodNotAllowedHttpException $exception) {

    try {
        return \Redirect::back()->withErrors(['Cheating?, you are not allowed to do that method. use proper method instead']);
        Log::warning('MethodNotAllowedHttpException', array('context' => $exception->getMessage()));
    } catch(Exception $e) {
        return Response::view('errors.404', array('code' => 'http_error_404'), 404);
    }


});

App::error(function(Exception $exception, $code)
{

    Log::error($exception);

    if (Config::get('app.debug')) {
        return;
    }


    switch ($code)
    {
        case 403: 
        case 404:            
        case 500:
            return \View::make('errors.'.$code, ['code' => $code]);
            break;
        case 405:
            echo 'test';
            die;
            return \View::make('errors.default');
            break;
        default:
            return \View::make('errors.default');
            break;
    }
});

