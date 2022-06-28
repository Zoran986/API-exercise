<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsersFormRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();

        return response()->json(['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersFormRequest $request)
    {
       // return ['here'];
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->address = $request->address;

        if($user->save())
        return response()->json(['success' => 'User created'], 200);

        return response()->json(['error' => 'An error occurred'], 500);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if(!$user)
        return response()->json(['error' => 'User does not exist', 499]);

        return response()->json(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if(!$user)
             return response()->json(['error' => 'User does not exist', 499]);

        if($request->has('name'))
            $user->name = $request->name;

        if($request->has('email'))
            $user->email = $request->email;
        
        if($request->has('password'))
        $user->password = Hash::make($request->password);

        if($request->has('address'))
        $user->address = $request->address;

        if($user->save())
        return response()->json([
            'success' => 'User updated',
            'user' => $user
        ], 200);

        return response()->json(['error' => 'An error occurred'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         User::where('id', $id)->delete();

         return response()->json(['success' => "User deleted!"]);
    }

     public function search(Request $request)
    {
       if(!$request->has('filter')) 
            return response()->json(['users' => []]);

       $filter = $request->filter;     
       $users = User::where('name', 'like', '%'.$filter.'%' )
                  ->orWhere('email', 'like', '%'.$filter.'%')
                  ->orWhere('address', 'like', '%'.$filter.'%')
                  ->get();

        return response()->json(['users' => $users]);
    
    }
}