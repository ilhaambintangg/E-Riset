<?php

namespace App\Http\Controllers\Admin\Edvokat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EdvokatDashboardController extends Controller
{
    /**
     * Display the Edvokat placeholder page.
     */
    public function index()
    {
        return view('admin.edvokat.index');
    }
}
