<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function test(Request $request)
    {
        // return $this->ok(['test' => '12']);
        return $this->error('这里是个错误！');
    }
}
