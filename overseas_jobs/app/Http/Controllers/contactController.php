<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class contactController extends Controller
{
  public function getlist()
 {
     $contact_list = DB::table('contact')->get();
     return response()->json(["contacts" => $contact_list]);
 }
 public function addContact(Request $request)
 {
   $data = [
     'name' => $request->name,
     'age' => $request->age,
     'job' => $request->job
   ];
   DB::table('contact')->insert($data);
   return response()->json(['msg'=>'message']);
 }
 public function updateContact($id,Request $request)
 {
   DB::table('contact')->where('id',$id)->update(['name'=>$request->name,'age'=>$request->age,'job'=>$request->job]);
   return response()->json(['msg'=>'message']);
 }
 public function deleteContact($id)
 {
   DB::table('contact')->where('id',$id)->delete();
   return response()->json(['msg'=>'message']);
 }
}