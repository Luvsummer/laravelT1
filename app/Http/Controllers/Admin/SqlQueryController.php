<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SqlQueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin');
    }

    public function index()
    {
        return view('admin.dev');
    }

    public function execute(Request $request)
    {
        $request->validate([
            'sql' => 'required|string'
        ]);

        $sql = trim($request->input('sql'));

        if (!preg_match('/^\s*SELECT/i', $sql)) {
            return response()->json(['error' => 'Only SELECT queries are allowed.'], 400);
        }

        try {
            $results = DB::select($sql);
            return response()->json(['data' => $results]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
