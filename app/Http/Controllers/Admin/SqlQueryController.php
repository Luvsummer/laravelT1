<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SqlRecord;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Response;

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
            $perPage = 5;
            $results = DB::select($sql);
            $total = count($results);
            $page = (int) $request->input('page', 1);
            $offset = ($page - 1) * $perPage;
            $pagedResults = array_slice($results, $offset, $perPage);

            SqlRecord::create([
                'user_id' => Auth::id(),
                'query' => $sql,
            ]);

            return response()->json([
                'data' => $pagedResults,
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {
            SqlRecord::create([
                'user_id' => Auth::id(),
                'query' => $sql,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exportCsv(Request $request)
    {
        $request->validate([
            'sql' => 'required|string',
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1|max:100',
        ]);

        $sql = trim($request->input('sql'));
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 10);

        if (!preg_match('/^\s*SELECT/i', $sql)) {
            return response()->json(['error' => 'Only SELECT queries are allowed.'], 400);
        }

        try {
            $results = DB::select($sql);
            $offset = ($page - 1) * $perPage;

            $pagedResults = array_slice($results, $offset, $perPage);
            if (empty($pagedResults)) {
                return response()->json(['error' => 'No data available for the selected page.'], 400);
            }

            $data = json_decode(json_encode($pagedResults), true);
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $csv->setDelimiter(",");
            $csv->setEnclosure('"');

            if (!empty($data)) {
                $csv->insertOne(array_keys($data[0]));
                foreach ($data as $row) {
                    $csv->insertOne(array_values($row));
                }
            }

            return Response::make($csv->toString(), 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="result_page_'.$page.'.csv"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid SQL or execution failed.'], 400);
        }
    }


    public function exportJson(Request $request)
    {
        $request->validate([
            'sql' => 'required|string',
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1|max:100',
        ]);

        $sql = trim($request->input('sql'));
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 10);

        if (!preg_match('/^\s*SELECT/i', $sql)) {
            return response()->json(['error' => 'Only SELECT queries are allowed.'], 400);
        }

        try {
            $results = DB::select($sql);
            $offset = ($page - 1) * $perPage;

            $pagedResults = array_slice($results, $offset, $perPage);
            if (empty($pagedResults)) {
                return response()->json(['error' => 'No data available for the selected page.'], 400);
            }

            return response()->json($pagedResults, 200, [
                'Content-Disposition' => 'attachment; filename="result_page_'.$page.'.json"',
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid SQL or execution failed.'], 400);
        }
    }

}
