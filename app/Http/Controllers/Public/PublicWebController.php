<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Requirement;
use App\Models\WebSetting;
use App\Models\Submission;

class PublicWebController extends Controller
{
    public function landing()
    {
        $faqs = Faq::orderBy('id')->get();
        $requirements = Requirement::orderBy('id')->get();
        $setting = WebSetting::first();
        
        $stats = [
            'total' => Submission::count(),
            'approved' => Submission::where('current_status', 'Disetujui')->count(),
            'processing' => Submission::whereIn('current_status', ['Menunggu Verifikasi', 'Sedang Diproses'])->count(),
        ];

        return view('pages.landing', compact('faqs', 'requirements', 'setting', 'stats'));
    }

    public function form()
    {
        $setting = WebSetting::first();
        return view('pages.form', compact('setting'));
    }

    public function track(Request $request)
    {
        $setting = WebSetting::first();
        $submission = null;
        $error = null;

        if ($request->has('registration_number')) {
            $registrationNumber = $request->input('registration_number');
            $submission = Submission::where('registration_number', $registrationNumber)->first();
            
            if (!$submission) {
                $error = 'Nomor registrasi tidak ditemukan.';
            }
        }

        return view('pages.track', compact('setting', 'submission', 'error'));
    }

    public function success($registration_number)
    {
        $setting = WebSetting::first();
        return view('pages.success', compact('setting', 'registration_number'));
    }
}
