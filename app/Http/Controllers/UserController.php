<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

use Validator;

class UserController extends Controller
{
    /**
     * This function returns a list of all users 
     * registered in the system with status code 200 
     * if at least one user is found.
     */
    public function index()
    {
        return response()->json(User::all(),200);
    }

    /**
     * Function store(), recieves the fields : 
     * name, email and Image
     * as input to create a system user. 
     * The fields name and email are considered required
     * and the image field is suposed to be an image.
     */
    public function store(Request $request)
    {

        //Set of validation rules

        $rules=[
            'name' =>'required|max:100',
            'email' =>'required|email',
            'Image' => 'sometimes | image | mimes:jpeg,png,jpg,gif,svg'
        ];
        $validator = Validator::make($request->all(),$rules);
        
        //If validation fails return error status code

        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        //Additional validation over image field comparing its size against max uplodad

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            if ($file->getClientSize() <= $file->getMaxFilesize()) {
                $fileName = $file->store('images');
            } else {
                return response()->json(['msg' => 'The image file is too big ' ],400);
            }
        }
        $parameters=$request->all();
        
        //Extra user attribute in case of token validation requirement
        
        $parameters['api_token']=\Str::random(60);

        //Attaching image system path to user attribute

        $parameters['image_path']=$fileName;

        return response()->json(User::create($parameters),201);
    }

    /**
     * Function update(), updates user info
     */

    public function update(Request $request,$id)
    {
        $user = User::find($id);
        
        //An user has to be registered to be updated

        if ($user) {  
            
            //Set of validation rules

            $rules=[
                'name' =>'required|max:100',
                'email' =>'required|email',
                'Image' => 'sometimes | image | mimes:jpeg,png,jpg,gif,svg'
            ];
            
            $validator = Validator::make($request->all(),$rules);
            
            //If validation fails return error status code
    
            if ($validator->fails()) {
                return response()->json($validator->errors(),400);
            }
            
            //Otherwise the user is updated

            $user->update($request->all());
            return response()->json($user,200);
        }

        //If not registered return error

        return response()->json(404);
    }

    /**
     * Function show() brings back all user information based on the
     * passed id
     */

    public function show($id)
    {
        $user = User::find($id);
        
        //If user found, user details are returned

        if ($user) {
            return response()->json($user,200);
        }

        //If there is no user found, return an error code

        return response()->json(404);
    }


    /**
     * Function delete(), deletes an user based on the passed id
     */

    public function delete($id)
    {
        $user = User::find($id);
        
        //If user is found, delete the user else return error code

        if ($user) {
            $user->delete();
            return response()->json(null,204);
        }
        return response()->json(404);
    }
}
