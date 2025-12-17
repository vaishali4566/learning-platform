<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\LessonProgressService;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{

//     Used by:
// Video (AJAX call)
// Text button
    public function complete(Request $request)
    {
        LessonProgressService::markCompleted(
            auth()->id(),
            $request->lesson_id
        );

        return response()->json(['status' => 'completed']);
    }
}
