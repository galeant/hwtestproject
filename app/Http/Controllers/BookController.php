<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class BookController extends Controller
{
    public function index(Request $request)
    {
        try {
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
