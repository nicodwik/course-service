<?php

namespace App\Http\Controllers;

use App\Course;
use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request) {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
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

        $isRatingExist = Review::where('course_id', $courseId)->where('user_id', $userId)->exists();
        if ($isRatingExist) {
            return response()->json([
                'status' => 'error',
                'message' => 'this course has been reviewed'
            ], 409);
        }


        $review = Review::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $review
        ], 200);
    }

    public function update(Request $request, $id) {
        $rules = [
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
        ];
        $data = $request->except('user_id', 'course_id');
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }

        $review = Review::find($id);
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        $review->update($data);
        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
    }
}
