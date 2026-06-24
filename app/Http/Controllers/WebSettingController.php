<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebSetting;

class WebSettingController extends Controller
{
    public function index()
    {
        $setting = WebSetting::first();
        if (!$setting) {
            $setting = WebSetting::create([
                'nama_instansi' => 'Pengadilan Tinggi Tanjungkarang',
                'alamat' => 'Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung, Lampung 35214',
                'telepon' => '(0721) 482436',
                'email' => 'info@pt-tanjungkarang.go.id',
                'website' => 'https://pt-tanjungkarang.go.id',
                'google_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.938835848525!2d105.25732131476686!3d-5.42617699606473!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da45a3db2be3%3A0xc6c4f034be8b2e1!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1684824000000!5m2!1sen!2sid" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'link_terkait' => [
                    ['title' => 'Mahkamah Agung RI', 'url' => 'https://mahkamahagung.go.id'],
                    ['title' => 'Badan Peradilan Umum', 'url' => 'https://badilum.mahkamahagung.go.id']
                ]
            ]);
        }
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = WebSetting::first();
        if (!$setting) {
            $setting = new WebSetting();
        }

        $validated = $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'google_maps' => 'nullable|string',
            'link_terkait' => 'nullable|array'
        ]);

        $setting->fill($validated);
        $setting->save();

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
