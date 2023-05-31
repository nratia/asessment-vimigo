<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function regStudent(Request $request): Response
    {

        // Create a new user
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'study_course' => $request->study_course,
        ]);

        // Generate a token for the user
        $token = $student->createToken('StudentToken')->accessToken;

        // Return the token as a response
        return Response(['status'=> 200,'access_token' => $token,'student'=> $student], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
