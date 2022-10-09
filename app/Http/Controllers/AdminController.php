<?php

namespace App\Http\Controllers;

use App\Models\completed_courses;
use App\Models\course;
use App\Models\course_skills;
use App\Models\EnrolledCourses;
use App\Models\jop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\need_course;
use App\Models\offer_course;
use App\Models\quiz;
use App\Models\supplier;
use App\Models\supplier_org;
use App\Models\test;
use App\Models\track;
use App\Models\track_course;

class AdminController extends Controller
{



    public function course_search($name)
    {
        if (auth()->user()->user_type == '1') {
            return course::where('name', 'like', '%' . $name . '%')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function track_search($name)
    {
        if (auth()->user()->user_type == '1') {
            return track::where('name', 'like', '%' . $name . '%')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function users_search($name)
    {
        if (auth()->user()->user_type == '1') {
            return User::where('name', 'like', '%' . $name . '%')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function course_count()
    {
        if (auth()->user()->user_type == '1') {
            return course::all()->count();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function student_count()
    {
        if (auth()->user()->user_type == '1') {
            return user::where('user_type', 0)->get()->count();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function supplier_count()
    {
        if (auth()->user()->user_type == '1') {
            return supplier::all()->count();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function add_track_course(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new track_course();
            $data->course_id = $request->course_id;
            $data->track_id = $request->track_id;
            return $data->save();
        } else {
            return 'Cant Access You Are Student not admin';
        }
    }
    public function addCourse(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $request->validate([
                'name' => 'required',
                'track_id' => 'required',
                'description' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
                'type' => 'required',
            ]);

            $data = course::create($request->all());
            return 'course_id=' . $data->id . ' if pre course add course needs /course_needs';
        } else {
            return 'Cant Access You Are Student not admin';
        }
    }
    public function course_needs($id, Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new need_course();
            $data->course_id = $id;
            $data->needs = $request->needs;
            $data->save();
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function update_course($id, Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = course::find($id);
            $data->update($request->all());
            return 'course updated Successfully if pre update needs';
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function update_needs($id, Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = need_course::find($id);
            return $data->update($request->all());
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function delete_course($id)
    {
        if (auth()->user()->user_type == '1') {
            $data = course::find($id);
            if (!$data)
                return abort('404');
            $data->need_course()->delete();
            $data->delete();
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function course_skills($id, Request $request)
    {
        $data = new course_skills();
        $data->course_id = $id;
        $data->skills = $request->skills;
        return $data->save();
    }
    public function completed_course(Request $request, $id)
    {
        if (auth()->user()->user_type == '1') {
            $data = new completed_courses();
            $data->user_id = $id;
            $data->course_id = $request->course_id;
            $data->start_at = $request->end_at;
            $data->end_at = $request->end_at;
            return $data->save();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function show_enrolled_course()
    {
        if (auth()->user()->user_type == '1') {
            return EnrolledCourses::all();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function student_quiz_show()
    {
        if (auth()->user()->user_type == '1') {
            return test::all();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function add_grade($id, Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = test::where('user_id', $id);
            $request->validate([
                'grade' => 'required',
            ]);
            return $data->update($request->all());
        } else {
            return 'You Are Student not admin';
        }
    }
    public function student_indexed()
    {
        //return student indexed as the most students enrolled courses to offer them a jop
        if (auth()->user()->user_type == '1') {
            $data = completed_courses::all();
            $user_id = [];
            $final_user_id = [];
            $users = [];
            foreach ($data as $datas) {
                $user_id[] = $datas->user_id;
            }
            $count_user_id = array_count_values($user_id);
            arsort($count_user_id);
            foreach ($count_user_id as $keys => $values) {
                $final_user_id[] = $keys;
            }

            for ($i = 0; $i < count($final_user_id); $i++) {
                $lap = User::where('id', $final_user_id[$i])->get();
                $users[] = $lap;
            }
            return $users;
        } else {
            return 'You Are Student not admin';
        }
    }
    public function course_indexed()
    {
        //return the most enrolled courses
        if (auth()->user()->user_type == '1') {
            return $data = EnrolledCourses::join('courses', 'enrolled_courses.course_id', '=', 'courses.id')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function add_supplier(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new supplier();
            $data->name = $request->name;
            $data->total = $request->total;
            $data->deposite = $request->deposite;
            return $data->save();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function add_supplier_course(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new supplier_org();
            $data->supplier_id = $request->supplier_id;
            $data->course_id = $request->course_id;
            return $data->save();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function show_supplier()
    {
        if (auth()->user()->user_type == '1') {
            return supplier::join('supplier_orgs', 'suppliers.id', '=', 'supplier_orgs.supplier_id')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function update_supplier($id, Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = supplier::find($id);
            $data->name = $request->name;
            $data->total = $request->total;
            $data->deposite = $request->deposite;
            return $data->save();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function search_supplier($name)
    {
        if (auth()->user()->user_type == '1') {
            return supplier::where('name', 'like', '%' . $name . '%')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function delete_supplier($id)
    {
        if (auth()->user()->user_type == '1') {
            $data = supplier::find($id);
            if (!$data)
                return abort('404');
            $data->supplier_orgs()->delete();
            $data->delete();
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function offer_jop(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new jop();
            $data->user_id = $request->user_id;
            $data->jop_name = $request->jop_name;
            return $data->save();
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function show_students_confirmation()
    {
        if (auth()->user()->user_type == '1') {
            return jop::all();
        } else {
            return 'Yuo Are Student not admin';
        }
    }
    public function student_courses_search($id)
    {
        if (auth()->user()->user_type == '1') {
            return $data = completed_courses::where('user_id', $id)->join('courses', 'completed_courses.course_id', '=', 'courses.id')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function search_student_by_course($id)
    {
        if (auth()->user()->user_type == '1') {
            return $data = completed_courses::where('course_id', $id)->join('users', 'completed_courses.user_id', '=', 'users.id')->get();
        } else {
            return 'You Are Student not admin';
        }
    }
    public function offer_course(Request $request)
    {
        if (auth()->user()->user_type == '1') {
            $data = new offer_course();
            $data->user_id = $request->user_id;
            $data->course_id = $request->course_id;
            return $data->save();
        } else {
            return 'You Are Student not admin';
        }
    }
}
