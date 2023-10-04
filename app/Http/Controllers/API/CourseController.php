<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //returning all the course lists
    public function courseList( Request $request){
    
        
        try{
            // $results = Course::select('id', 'name', 'thumbnail','video', 'lesson_num', 'price')->get();
        $results = Course::get(['id', 'name', 'thumbnail','video', 'description','lesson_num', 'price']);

        return response()->json([
            'code' => 200,
            'msg' => 'My course list is here',
            'data' => $results
        ]);
        }
        catch(Exception $e){ /// Exception or Throwable
             
            return response()->json([
                'code' => $e->getCode()??400,
                'msg' => $e->getMessage(),
                'data' => null
            ]);
        }

    }
    
    public function courseDetail( Request $request){
        //course id
        $id = $request->id;
        // return "id".$request->id;
        
        try{
            // $results = Course::where('id',$id)->select('id', 'name', 'thumbnail','video', 'lesson_num', 'price')->get();
        $results = Course::where('id',$id)->get([
            'id',
            'name',
            'user_token',
            'description',
            'price',
            'lesson_num',
            'video_length',
            'thumbnail',
            'video',
        ]);
        // $results = Course::where('id',$id)->get(['id', 'name', 'thumbnail','video', 'description','lesson_num', 'price'])->first();

        // $results = Course::where('id',$id)->get();
        return response()->json([
            'code' => 200,
            'msg' => 'My course detail is here',
            'data' => $results
        ]);
        }
        catch(Exception $e){ /// Exception or Throwable
             
            return response()->json([
                'code' => $e->getCode()??400,
                'msg' => $e->getMessage(),
                'data' => null
            ]);
        }

    }
}
