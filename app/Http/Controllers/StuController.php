<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;

class StuController extends Controller
{
    
    public function index(){
        $stu = student::orderBy('id','ASC')->get(); //DESC
        return view('index',['data'=>$stu]);
    }
    public function store(Request $req){
        if($req->ajax()){
            $validator = $this->validate($req,[
                'name'=>'required',
                'email'=>'required|email|unique:student,email',
                'password'=>'required'
            ]);
            
                $stu = New student;
                $stu->name = $req->name;
                $stu->email = $req->email;
                $stu->password = $req->password;
                $stu->save();
                return response()->json($stu);
        }
    }
    public function destroy($id){
        $stu = student::find($id);
        $stu->delete();
        return response()->json(['success'=>'Product deleted successfully.']);
    }
    public function edit($id){
        $stu = student::find($id);
        return response()->json($stu);
    }
    public function update(Request $req){
        $stu = student::find($req->id);
        $stu->name = $req->name;
        $stu->email = $req->email;
        $stu->password = $req->password;
        $stu->save();
        return response()->json(['update'=>'កែសម្រួលជោគជយ័!']);
    }
}
