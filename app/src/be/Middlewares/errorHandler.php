<?php

/**
 * Error handling
 */
use Symfony\Component\HttpFoundation\Response;
use WORDR\Exceptions;

$app->error(function (\Exception $e, $code) use ($app) {

    if ($e->getCode() > 0) {
        $code = $e->getCode();
    }

    $app['logger']->addError('API ERROR :: ' . $e->getMessage() . ' :: file :: ' . $e->getFile(). ' :: get Line :: ' . $e->getLine(), $e->getTrace());

    return new Response($e->getMessage(), $code);
});
