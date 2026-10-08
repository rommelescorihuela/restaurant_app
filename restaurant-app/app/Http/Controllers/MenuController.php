<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Table;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with('dishes')->where('is_active', true)->get();
        $table = null;

        if ($request->has('table')) {
            $table = Table::find($request->integer('table'));
        }

        return view('menu', compact('categories', 'table'));
    }
}
