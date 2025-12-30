<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\PanelCertificatesResource;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $pageTitle = 'گواهینامه‌ها';
        $user = auth()->user();
        $query = Certificate::with('course')->where('user_id',$user->id)->get();
        $certificates = PanelCertificatesResource::collection($query);
        return inertia('Panel/Certificates/Index', compact('certificates','pageTitle'));
    }
}
