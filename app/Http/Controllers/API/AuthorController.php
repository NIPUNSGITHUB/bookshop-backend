<?php

namespace App\Http\Controllers\API;

use App\Models\Author;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;

class AuthorController extends UserController
{
    public function authorList()
    {
        return $this->userList(0);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {         
        return $this->createUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function search($authorId)
    { 
        return $this->userInfo($authorId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $authorId)
    {
        return $this->updateUser($authorId,$request);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($author)
    {
        return $this->deleteUser($author);
    }
}
