<?php

namespace App\Http\Controllers;

use App\Course;
use App\MyCourse;
use Illuminate\Http\Request;

class MyCourseController extends Controller
{
    public function index(Request $request) {
        $myCourses = MyCourse::query()->with('course');

        if($userId = $request->query('user_id')) {
            $myCourses->where('user_id', $userId);
        }

        return response()->json([
            'status' => 'success',
            'data' => $myCourses->get()
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
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

        $userId = \Request('user_id');
        $getUser = getUser($userId);
        if ($getUser['status'] === 'error') {
            return response()->json([
                'status' => $getUser['status'],
                'message' => $getUser['message']
            ], $getUser['http_code']);
        }
        // dd($getUser);
        $isCourseExist = MyCourse::where('course_id', $courseId)->where('user_id', $userId)->exists();
        if ($isCourseExist) {
            return response()->json([
                'status' => 'error',
                'message' => 'this course already taken'
            ], 409);
        }

        if ($course->price === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'price cannot be null'
            ]);
        }

        if ($course->type === 'premium') {
            $order = postOrder([
                'user' => $getUser['data'],
                'course' => $course->toArray()
            ]);
            
            if ($order['status'] === 'error') {
                return response()->json([
                    'status' => $order['status'],
                    'message' => $order['message']
                ], $order['http_code']);
            }

            return response()->json([
                'status' => $order['status'],
                'data' => $order['data']
            ]);
        } else {
            $myCourse = Mycourse::create($data);
            return response()->json([
                'status' => 'success',
                'data' => $myCourse
            ], 200);
        }


    }

    public function createPremiumAccess(Request $request) {
        
        $data = $request->all();
        $myCourse = Mycourse::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $myCourse
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
