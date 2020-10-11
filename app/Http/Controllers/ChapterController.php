<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Chapter;

class ChapterController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];
        $data = $request->all();
        $validate = \Validator::make($data, $rules);

        if ($validate->fails()) {
            return->response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $courseId = \Request('course_id');
        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $chapter = Chapter::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ], 200);
    }
}
