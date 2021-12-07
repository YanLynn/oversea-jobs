<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Countries;
use Auth;
class DataTableManualController extends Controller
{
    public function index(Request $request)
    {    
        $columns = ['continent_name', 'country_name'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = Countries::select('id', 'continent_name', 'country_name');

        if ($searchValue) {
            $query->where(function($query) use ($searchValue) {
                $query->where('continent_name', 'like', '%' . $searchValue . '%')
                ->orWhere('country_name', 'like', '%' . $searchValue . '%');
            });
        }

        $projects = $query->orderBy($columns[$request->input('column')], 'desc')->paginate($length);
        return response()->json(array("countriesList"=>$projects));

    }

    
}
     