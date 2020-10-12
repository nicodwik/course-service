<?php

namespace App\Http\Controllers;

use App\Course;
use App\Chapter;
use App\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{

    public function index(Request $request) {
        $lessons = Lesson::query();

        if ($chapter_id = $request->query('chapter_id')) {
            $lessons->where('chapter_id', '=', $chapter_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lessons->get()
        ], 200);
    }

    public function show($id) {
        
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ], 200);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }

        $chapterId = \Request('chapter_id');
        $chapter = Chapter::find($chapterId);

        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $lesson = Lesson::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ], 200);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];
        $data = $request->all();
        $validate = \Validator::make($data, $rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        $chapterId = \Request('chapter_id');
        $chapter = Chapter::find($chapterId);
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        $lesson->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ], 200);
    }

    public function destroy($id) {
        
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }
        $lesson->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'lesson '. $lesson->name .' successfully deleted'
        ], 200);
    }
}
