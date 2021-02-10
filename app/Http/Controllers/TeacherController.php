<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function __construct(Request $request) 
    {
        $this->middleware(function ($request, $next) {
            if (!$request->session()->has('schoolData')) {
                return redirect('/select_school');
            }
            $school = session('schoolData');
            $school_code = $school->school_code;
            $name = $school_code.'_teacher';

            if (Auth::guard($name)->user() == '') {
                return redirect(route('teacher.login'));
            }
            $this->model_name = 'auth:'.$school_code.'_teacher';
            return $next($request);
        });
    }

    public function index(Request $request){
        $metadata = [
            'title' => '| TEACHER PORTAL',
            'description' => 'Welcome to your teacher portal at Students Result Management System',
            'keywords' => 'Teacher, Result checker, Information, Teacher portal, Teacher data, Teacher activities',
            'body_pics' => 'body2'
        ];

        if (!$request->session()->has('schoolData')) {
            return redirect('/select_school');
        }
        $model_name = session('schoolData')->school_code.'_teacher';


        return view('teacher.teacher', [
            'metadata' => $metadata,
            'model_name' => $model_name,
            'school' => session('schoolData'),
            'user' => Auth::guard($model_name)->user()
        ]);
    }
}