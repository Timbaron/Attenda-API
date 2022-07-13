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

    public function create($course_id){
        if($course_id == null){
            return response()->json([
                'status' => 'error',
                'message' => 'No course found'
            ]);
        }
        // create attendance
        else {
            $attendance = Attend::create([
                'attendance_id' => uniqid('ATD-'),
                'course_id' => $course_id,
                'attendees' => json_encode([]),
            ]);
            if ($attendance) {
                return response()->json([
                    'attendance' => $attendance,
                    'message' => 'Attendance created successfully',
                    'status' => 'success'
                ]);
            }
        }

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
