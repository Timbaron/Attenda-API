<?php

namespace App\Http\Controllers;

use App\Models\Attend;
use App\Models\Course;
use Illuminate\Http\Request;

class AttendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Create a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function create(){
        // get all lecturers courses
        $courses = Course::whereLecturer(auth()->user()->id)->latest()->get();
        $courseCount = $courses->count();

        // create attendance
        $attendance = Attend::create([
            'attendance_id' => uniqid('ATD-'),
            'attendees' => json_encode([]),
        ]);
        if (count($courses) > 0) {
            return response()->json([
                'courses' => $courses,
                'attendance' => $attendance,
                'total' => $courseCount,
                'message' => 'Attendance created successfully'
            ]);
        }
        return response()->json([
            'message' => 'You have no courses to retrieve, please create one and try again!!'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if course attendant already exist
        $attendance = Attend::whereAttendance_id($request->attendance_id)->first();
        if(empty($attendance)){
            return response()->json([
                'message' => 'Attedance not found',
            ]);
        }
        $attendees = json_decode($attendance->attendees);

        // Check if student has already marked their attendance
        if(in_array($request->student_id ,$attendees)){
            $response = ['message' => $request->student_id . ' Already exist','status'=> 'error'];
            return response($response, 200);
        }
        else {
            array_unshift($attendees, $request->student_id);
            $attendance->update([
                'attendees' => json_encode($attendees)
            ]);
            $response = ['message' => $request->student_id.' Has been submitted', 'status' => 'success'];
            return response($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance = Attend::whereAttendance_id($id)->first();
        $attendees = json_decode($attendance->attendees);

        $response = [
            'attendance' => $attendance,
            'attendees' => $attendees,
            'count' => count($attendees),
        ];
        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attend $attend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attend  $attend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $attend = Attend::whereAttendance_id($request->attendance_id)->first();
        if($attend){
            $attend->delete();
            $response = [
                'status' => 'success',
                'message' => 'Attendance deleted successfully'
            ];
            return response($response, 200);
        }
        else{
            $response = [
                'status' => 'error',
                'message' => 'Attendance not found'
            ];
            return response($response, 500);
        }
    }
}
