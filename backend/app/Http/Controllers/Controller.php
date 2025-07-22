<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //

    public function sendResponse($data, $message = '', $status = 200)
    {
        return response()->json(['data' => $data, 'message' => $message], $status);
    }

    public function sendError($message, $status = 400)
    {
        return response()->json(['error' => $message], $status);
    }
}
