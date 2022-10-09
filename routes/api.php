<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/admin_register', [AuthController::class, 'admin_register']);
    Route::post('/admin/student_count', [AdminController::class, 'student_count']);
    Route::post('/admin/course_count', [AdminController::class, 'course_count']);
    Route::post('/admin/course_search/{name}', [AdminController::class, 'course_search']); //
    Route::post('/admin/users_search/{name}', [AdminController::class, 'users_search']); //
    Route::post('/admin/track_search/{name}', [AdminController::class, 'track_search']);
    Route::post('/admin/course_skills/{id}', [AdminController::class, 'course_skills']);
    Route::post('/admin/show_enrolled_course', [AdminController::class, 'show_enrolled_course']);
    Route::post('/admin/completed_course/{id}', [AdminController::class, 'completed_course']);
    Route::post('/admin/student_quiz_show', [AdminController::class, 'student_quiz_show']);
    Route::post('/admin/add_grade/{id}', [AdminController::class, 'add_grade']);
    Route::post('/admin/supplier_count', [AdminController::class, 'supplier_count']);
    Route::post('/admin/addCourse', [AdminController::class, 'addCourse']);
    Route::post('/admin/course_needs/{id}', [AdminController::class, 'course_needs']);
    Route::post('/admin/update_course/{id}', [AdminController::class, 'update_course']);
    Route::post('/admin/update_needs/{id}', [AdminController::class, 'update_needs']);
    Route::post('/admin/delete_course/{id}', [AdminController::class, 'delete_course']);
    Route::post('/admin/student_indexed', [AdminController::class, 'student_indexed']); //return the students in order to their ompleted courses
    Route::post('/admin/course_indexed', [AdminController::class, 'course_indexed']); //return the most enrolled courses
    Route::post('/admin/add_supplier', [AdminController::class, 'add_supplier']);
    Route::post('/admin/add_supplier_course', [AdminController::class, 'add_supplier_course']);
    Route::post('/admin/show_supplier', [AdminController::class, 'show_supplier']);
    Route::post('/admin/update_supplier/{id}', [AdminController::class, 'update_supplier']);
    Route::post('/admin/search_supplier/{name}', [AdminController::class, 'search_supplier']);
    Route::post('/admin/delete_supplier/{id}', [AdminController::class, 'delete_supplier']);
    Route::post('/admin/offer_jop', [AdminController::class, 'offer_jop']);
    Route::post('/admin/show_students_confirmation', [AdminController::class, 'show_students_confirmation']);
    Route::post('/admin/student_courses_search/{id}', [AdminController::class, 'student_courses_search']); //return the specific studnet with all his courses
    Route::post('/admin/search_student_by_course/{id}', [AdminController::class, 'search_student_by_course']); //return all students whose take this course
    Route::post('/admin/offer_course', [AdminController::class, 'offer_course']);
    Route::post('/admin/add_track_course', [AdminController::class, 'add_track_course']);






    Route::post('/enroll_course/{id}', [StudentController::class, 'enroll_course']);
    Route::post('/quiz/{id}', [StudentController::class, 'quiz']);
    Route::post('/my_courses', [StudentController::class, 'my_courses']);
    Route::post('/completed_courses', [StudentController::class, 'completed_courses']);
    Route::post('/exam_grade', [StudentController::class, 'exam_grade']);
    Route::post('/offer_jops_confrimation/{id}', [StudentController::class, 'offer_jops_confrimation']);
    Route::post('/show_student_jops', [StudentController::class, 'show_student_jops']);
    Route::post('/offer_course', [StudentController::class, 'offer_course']);
    Route::post('/recommandition/{id}', [StudentController::class, 'recommandition']);
    Route::post('/show_recommandation/{id}', [StudentController::class, 'show_recommandation']);
    Route::post('/courses', [StudentController::class, 'courses']);
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
