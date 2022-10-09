<?php

namespace App\Http\Controllers;

use App\Models\completed_courses;
use Illuminate\Http\Request;
use App\Models\EnrolledCourses;
use App\Models\course;
use App\Models\jop;
use App\Models\need_course;
use App\Models\offer_course;
use App\Models\quiz;
use App\Models\recommandition;
use App\Models\test;
use App\Models\track_course;
use App\Models\User;


use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function enroll_course($id, Request $request)
    {
        if (Auth::id()) {
            $check = course::find($id);

            if ($check->type == 'pre') {
                $need = need_course::with('course')->get('needs');
                $user_skill = completed_courses::where('user_id', auth()->user()->id)
                    ->join('course_skills', 'completed_courses.course_id', '=', 'course_skills.course_id')->get();
                $quiz = test::where('user_id', auth()->user()->id)->where('course_id', $id)->get();
                $quizs = [];
                $skills = [];
                $needs = [];

                foreach ($need as $v) {
                    $needs[] = $v['needs'];
                }
                foreach ($quiz as $v) {
                    $quizs[] = $v->grade;
                }

                foreach ($user_skill as $user_skills) {
                    $skills[] = $user_skills['skills'];
                }




                if (count($needs) <= count($skills)) {
                    for ($i = 0; $i < count($skills); $i++) {
                        if (!array_search($needs[$i], $skills)) {
                            return ' You Are Not Qualified for this course you have skills but not enough if you have these skills you can make a quiz the course needs is ' . print_r($needs) . 'Course_id=' . $id;
                        } else {
                            $data = EnrolledCourses::where('user_id', auth()->user()->id)
                                ->where('course_id', $id)->get();
                            if ($data->isEmpty()) {
                                $datas = new EnrolledCourses();
                                $datas->course_id = $id;
                                $datas->user_id = auth()->user()->id;
                                $datas->start_time = $request->start_time;
                                $datas->end_time = $request->end_time;
                                $datas->save();
                                return 'You Registered for this course successfully';
                            } else {
                                return "You Alreday Registered to this course";
                            }
                        }
                    }
                } elseif (!empty($quizs)) {
                    $data = EnrolledCourses::where('user_id', auth()->user()->id)
                        ->where('course_id', $id)->get();
                    if ($data->isEmpty()) {
                        $datas = new EnrolledCourses();
                        $datas->course_id = $id;
                        $datas->user_id = auth()->user()->id;
                        $datas->start_time = $request->start_time;
                        $datas->end_time = $request->end_time;
                        $datas->save();
                        return 'You Register for this course succefully';
                    } else {
                        return "You Alreday Registered to this course";
                    }
                } else {
                    return 'You Are Not Qualified for this course if you have these skills you can make a quiz the course needs is' . print_r($needs) . 'Course_id=' . $id;
                }
            } else {
                $data = EnrolledCourses::where('user_id', auth()->user()->id)
                    ->where('course_id', $id)->get();
                if ($data->isEmpty()) {
                    $datas = new EnrolledCourses();
                    $datas->course_id = $id;
                    $datas->user_id = auth()->user()->id;
                    $datas->start_time = $request->start_time;
                    $datas->end_time = $request->end_time;
                    $datas->save();
                    return 'You Register for this course succefully';
                } else {
                    return "You Alreday Registered to this course";
                }
            }
        }
    }
    public function quiz($id)
    {
        if (Auth::id()) {
            $data = new test();
            $data->user_id = auth()->user()->id;
            $data->course_id = $id;
            $data->save();
        }
    }
    public function my_courses()
    {
        if (Auth::id()) {
            return EnrolledCourses::where('user_id', auth()->user()->id)->join('courses', 'enrolled_courses.course_id', '=', 'courses.id')->get('name');
        }
    }
    public function completed_courses()
    {
        if (auth::id()) {
            return completed_courses::where('user_id', auth()->user()->id)->join('courses', 'completed_courses.course_id', '=', 'courses.id')->get('name');
        }
    }
    public function  exam_grade()
    {
        if (auth::id()) {
            return test::where('user_id', auth()->user()->id)->get();
        }
    }

    public function offer_jops_confrimation(Request $request, $id)
    {
        if (auth::id()) {
            $data = jop::where('user_id', auth()->user()->id)->where('id', $id);
            $request->validate([
                'student_confrimation' => 'required',
                'phone' => 'required',
            ]);
            return $data->update($request->all()) . 'we will contact you';
        }
    }
    public function show_student_jops()
    {
        if (auth::id()) {
            return jop::where('user_id', auth()->user()->id)->get();
        }
    }
    public function offer_course()
    //offering courses for users by the track
    {
        if (auth::id()) {
            $user_courses = completed_courses::where('user_id', auth()->user()->id)->join('track_courses', 'completed_courses.course_id', '=', 'track_courses.course_id')->get();
            $track_id = [];
            $course_id = [];
            $final = [];
            foreach ($user_courses as $v) {
                $track_id[] = $v->track_id;
            }
            for ($i = 0; $i < count($track_id); $i++) {
                $lap = track_course::where('track_id', $track_id[$i])->get('course_id');
                $course_id[] = $lap;
            }
            foreach ($course_id as $keys => $values) {
                foreach ($values as $sup_row) {
                    $final[] = $sup_row->course_id;
                }
            }
            $courses_id = array_unique($final);
            return $courses = course::find($course_id[0]);
        }
    }
    public function recommandition($id, Request $request)
    {
        if (auth::id()) {
            $data = recommandition::where('user_id', auth()->user()->id)->where('course_id', $id)->get();

            if ($data->isEmpty()) {
                $rec = new recommandition();
                $rec->user_id = auth()->user()->id;
                $rec->course_id = $id;
                $rec->recommandation = $request->recommandation;
                return $rec->save();
            } else {
                return 'you already recomandate this course before';
            }
        }
    }
    public function show_recommandation($id)
    {
        if (auth::id()) {
            $data = recommandition::where('course_id', $id)->get('recommandation');
            $sum = [];
            foreach ($data as $datas) {
                $sum[] = $datas->recommandation;
            }
            return array_sum($sum) / count($sum);
        }
    }
    public function courses()
    {
        return course::all();
    }
}
