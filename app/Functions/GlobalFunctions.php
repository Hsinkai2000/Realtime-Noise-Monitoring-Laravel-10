<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


function render_ok($message)
{
    response()->json($message, Response::HTTP_OK)->send();
}

function render_unprocessable_entity($message)
{
    Log::error('Unprocessable Entity', ['exception' => $message]);
    response()->json(
        ["Unprocessable Entity" => $message],
        Response::HTTP_UNPROCESSABLE_ENTITY
    )->send();
}

function render_error($message)
{
    Log::error('Error', ['exception' => $message]);
    response()->json(["Internal Server Error" => $message->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR)->send();
}

function render_not_found($message)
{
    Log::error('Not found', ['exception' => $message]);
    response()->json(["Not Found" => $message], Response::HTTP_NOT_FOUND)->send();
}

function linearise_leq($leq)
{
    return pow(10, $leq / 10);
}

function convert_to_db($avg_leq)
{
    return 10 * log10($avg_leq);
}
