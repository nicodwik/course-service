<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mentor;

class MentorController extends Controller
{
    public function index() {
        $mentors = Mentor::all();

        return response()->json([
            'status' => 'success',
            'data' => $mentors
        ], 200);
    }

    public function show($id) {
        $mentor = Mentor::find($id);

        if(!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ], 200); 
    }

    public function create(Request $request) {
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'email' => 'required|email',
            'profession' => 'required|string'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }

        $mentor = Mentor::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ], 200);
    }

    public function update(Request $request, $id) {
        $mentor = Mentor::find($id);
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }
        
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'email' => 'required|email',
            'profession' => 'required|string'
        ];
        $data = $request->all();
        $validation = \Validator::make($data, $rules);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }
        $mentor->update($data);

        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ], 200);
    }
}
