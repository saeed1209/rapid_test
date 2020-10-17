<?php

namespace App\Http\Controllers;


use Response;

class AppBaseController extends Controller
{
    public function sendResponse($result, $code, $message)
    {
        return Response::json( ['data'=>$result, 'message'=>$message], $code);

    }

    public function sendError($message, $code = 404)
    {
        return Response::json(["message"=>$message], $code);
    }
}