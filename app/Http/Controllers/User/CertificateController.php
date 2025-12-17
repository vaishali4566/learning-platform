<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    

    public function download(Course $course)
    {
        $user = Auth::user();

        // Generate a certificate number if not exists
        $certificate = Certificate::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'issued_at' => now(),
                'certificate_number' => Str::upper(Str::random(10)), // random 10-character code
            ]
        );

        $data = [
            'user_name' => $user->name,
            'course_name' => $course->title,
            'certificate_number' => $certificate->certificate_number,
            'issued_at' => $certificate->issued_at->format('d M, Y'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.certificates.template', $data);

        return $pdf->download("certificate-{$certificate->certificate_number}.pdf");
    }

}
