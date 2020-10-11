<?php

namespace App\Http\Controllers;

use App\Course;
use App\Mentor;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request) {
        $course = Course::query();

        if ($search = $request->query('search')) {
            // $course->when($search, function($query) use ($search) {
            //     return $query->whereRaw("name LIKE '%". strtolower($search). "%' ");
            // });
            
            $course->whereRaw("name LIKE '%". strtolower($search) ."%'");
        }
        
        if ($status = $request->query('status')) {
            // $course->when($status, function($query) use ($status) {
            //     return $query->where('status', '=', $status);
            // });
    
            $course->where('status', '=', $status);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $course->paginate(5)
        ]);
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string',
            'hasCertificate' => 'required|boolean',
            'thumbnail' => 'required|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'required|integer'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }

        $mentorId = \Request('mentor_id');
        $mentor = Mentor::find($mentorId);

        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $course =  Course::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $course
        ], 200);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'required|string',
            'hasCertificate' => 'required|boolean',
            'thumbnail' => 'required|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advance',
            'description' => 'string',
            'mentor_id' => 'required|integer'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }

        $course = Course::find($id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $mentorId = \Request('mentor_id');
        $mentor = Mentor::find($mentorId);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        $course->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $course
        ], 200);
    }
}
