<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 成功后提示
     * @param array $data
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok(array $data = [], string $msg = '')
    {
        return response()->json(['code' => 200, 'msg' => $msg, 'data' => $data]);
    }

    /**
     * 失败后提示
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $msg = '')
    {
        return response()->json(['code' => 500, 'msg' => $msg]);
    }
}
