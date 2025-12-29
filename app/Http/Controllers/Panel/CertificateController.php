<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $pageTitle = 'گواهینامه‌ها';
        return inertia('Panel/Certificates/Index', compact('pageTitle'));
    }
}
