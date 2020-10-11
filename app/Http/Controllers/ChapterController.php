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
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $courseId = \Request('course_id');
        $course = Course::find($courseId);

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

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];
        $data = $request->all();
        $validate = \Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $chapter = Chapter::find($id);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $courseId = \Request('course_id');
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $chapter->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ], 200);
    }
}
