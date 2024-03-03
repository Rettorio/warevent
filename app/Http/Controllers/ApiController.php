<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    public function getData()
    {
        return response()->json(['message' => 'Hello from Lumen API']);
    }
}