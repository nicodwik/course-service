<?php

namespace App\Http\Controllers;

use App\Course;
use App\ImageCourse;
use Illuminate\Http\Request;

class ImageCourseController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'image' => 'required|string',
            'course_id' => 'required|integer'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
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

        $image = ImageCourse::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $image
        ], 200);
    }

    public function destroy($id) {
        
        $image = ImageCourse::find($id);
        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'image not found'
            ], 404);
        }
        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'image successfully deleted'
        ], 200);
    }
}
