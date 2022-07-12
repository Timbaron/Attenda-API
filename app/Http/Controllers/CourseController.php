<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::whereLecturer(auth()->user()->id)->latest()->get();
        $courseCount = $courses->count();
        $user = auth()->user();

        if(count($courses) > 0){
            return response()->json([
                'courses' => $courses,
                'total' => $courseCount,
                'user' => $user,
                'message' => 'Courses has been retrieved successfully'
            ]);
        }
        return response()->json([
            'message' => 'No courses found'
        ]);
        // return $courses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'code' => 'required|string|unique:courses',
            'total_students' => 'required|integer',
            'lecturer' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all(), 'status' => 'error'], 422);
        }
        $request['course_id'] = uniqid('COU-');
        $course = Course::create($request->all());

        if($course){
            $response = ['course' => $course, 'status' => 'success', 'message' => 'Course Created successfully'];
            return response($response, 200);
        } else {
            $response = ["message" => ["Course Creation failed"],'status' => 'error'];
            return response($response, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($course)
    {
        $course = Course::whereId($course)->first();
        $attendance = Attend::where('course_id',$course->course_id)->latest()->get();
        if(!empty($course)){
            $response = [
                'course' => $course,
                'attendance' => $attendance,
                'message' => 'Course Retrived successfully'
            ];
            return response($response, 200);
        } else {
            $response = ["message" => "Course not found"];
            return response($response, 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $course)
    {
        $course = Course::whereId($course)->first();
        $course->update($request->all());
        $response = ['course'=> $course, 'message'=>'Course updated successfully'];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($course)
    {
        $course = Course::whereId($course)->firstorFail();
        $course->delete();
        $response = ['message' => 'Course deleted successfully'];
        return response($response, 200);

    }
}
