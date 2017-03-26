<?php

namespace sigc\Http\Controllers;

use Illuminate\Http\Request;

use sigc\Http\Requests;
use sigc\Http\Controllers\Controller;

use sigc\User;
use Auth;
use Input;
use Hash;
use Redirect;

class authController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.login');
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
    public function store(Request $request)
    {
        //echo dd(Hash::make(123456));

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            return redirect()->intended('/');
        }


        
        $user = User::where('username',$request->username)->get()->first();

        if($user==null)
        {//no existe
            echo dd("NO existe");
        }
        
        if(is_null($user->password))
        {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        if (Hash::needsRehash($user->password))
        {
            $user->password = Hash::make($user->password);
            $user->save();
        }
        echo $request->password;
        if(Hash::check($request->password, $user->password))
        {
            Auth::login($user);
            return Redirect::to('/');
        }else{
            echo dd("Existe pero el pass es incorrecto");
        }
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
