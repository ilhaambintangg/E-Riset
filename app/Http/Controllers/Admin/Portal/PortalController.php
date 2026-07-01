<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Display the Admin Portal page.
     */
    public function index()
    {
        return view('admin.portal.index');
    }
}
