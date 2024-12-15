<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class StudentController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->query('search'); // Capture the search query from request
        $perPage = $request->query('limit', 10); // Items per page, default is 10

        // Ensure limit is a valid number
        if (!is_numeric($perPage) || $perPage <= 0) {
            $perPage = 10; // Default to 10 if the provided limit is invalid
        }

        // Query students with optional search filter
        $query = Student::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('course', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%');
        }

        // Apply pagination
        $students = $query->paginate($perPage);

        // Check if records exist
        if ($students->total() > 0) {
            return response()->json([
                'status' => 200,
                'students' => $students->items(), // Only return the items for the current page
                'pagination' => [
                    'current_page' => $students->currentPage(),
                    'total_pages' => $students->lastPage(),
                    'total_items' => $students->total(),
                    'per_page' => $students->perPage(),
                    'next_page_url' => $students->nextPageUrl(),
                    'prev_page_url' => $students->previousPageUrl(),
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Records Found'
            ], 404);
        }
    }

  
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors' => $validator->messages()
            ],422);
        }else{
            $student = Student::create([
                'name'=> $request->name,
                'course'=> $request->course,
                'email'=> $request->email,
                'phone'=> $request->phone,
            ]);

            if($student){
                return response()->json([
                    'status'=> 200,
                    'message'=> "Student Created Successfully!"

                ],200);
            }else{
                return response()->json([
                    'status'=> 500,
                    'message'=> "Something Went Wrong!"  
                ],500);
            }
        }
    }

    public function show($id){
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status'=> 200,
                'student'=> $student

            ],200);           
        }else{
        
            return response()->json([
                'status'=> 404,
                'message'=> "No Such Student Found!"  
            ],404);          
        }
    }

    public function edit($id){
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status'=> 200,
                'student'=> $student

            ],200);           
        }else{
        
            return response()->json([
                'status'=> 404,
                'message'=> "No Such Student Found!"  
            ],404);          
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=> 422,
                'errors' => $validator->messages()
            ],422);
        }else{
            $student = Student::find($id);




            

            if($student){

                $student->update([
                    'name'=> $request->name,
                    'course'=> $request->course,
                    'email'=> $request->email,
                    'phone'=> $request->phone,
                ]);


                return response()->json([
                    'status'=> 200,
                    'message'=> "Student Updated Successfully!"

                ],200);
            }else{
                return response()->json([
                    'status'=> 404,
                    'message'=> "No Such Student Found!"  
                ],404);
            }
        }     
    }

    public function destroy($id){
        $student = Student::find($id);
        if($student){
            $student->delete();
            return response()->json([
                'status'=> 200,
                'message'=> "Student Deleted Successfully"  
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'message'=> "No Such Student Found!"  
            ],404);

        }
    }
}
