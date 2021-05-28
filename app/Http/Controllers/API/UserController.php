<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'isAdmin' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('token')->accessToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    
    }

     /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('token')-> accessToken; 
            $success['name'] =  $user->name;
            $success['email'] =  $user->email;
            $success['isAdmin'] =  $user->isAdmin;
            $success['id'] =  $user->id;
            
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }


    public function logout()
    { 
        if (Auth::check()) {
           Auth::user()->AauthAcessToken()->delete();
        }
    }


     /**
     * Update User api
     *
     * @return \Illuminate\Http\Response
     */
    public function updateUser($userId,$request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId, 
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
       
        $user = User::find($userId);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save(); 
        
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User update successfully.');
    
    }

     /**
     * Delete User api
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUser($userId)
    {
        $user = User::find($userId); 
        $user->isActive = 0;
        $user->save();
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User Delete successfully.');
    }


     /**
     * User list api
     *
     * @return \Illuminate\Http\Response
     */
    public function userInfo($userId)
    {
        $user = User::find($userId);  
        return response()->json(['success' => $user], 200);
    }

    public function userList($isAdmin)
    {
        //Author
        $user = User::where('isAdmin',$isAdmin)->where('isActive',1)->get();  
        return response()->json(['success' => $user], 200);
    }

    public function AllUsers()
    {
         //Author
         $user = User::all();  
         return response()->json(['success' => $user], 200);
    }

    public function changeUserStatus($userId,$currentstatus)
    {
        $user = User::find($userId);  
        $user->isActive = $currentstatus == 1 ? 0 : 1;
        $result = $user->save();
        return $this->sendResponse($result, 'User status change successfully.');

    }
}
