<?php
namespace App\Traits;

trait ApiResponses
{
    protected function ok($message, $data)
    {
        return $this->success($message, $data);
    }

    protected function success($message, $data, $code = 200)
    {
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    protected function error($message, $code)
    {
        return response()->json(['message' => $message], $code);
    }
}
