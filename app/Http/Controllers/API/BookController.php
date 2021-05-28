<?php

namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Book;
use Validator; 
use DB;

class BookController extends Controller
{
   
    public function show()
    {
        return Book::select('books.*','users.name')
        ->join('users', 'users.id', '=', 'books.userId')
        ->where('books.isActive',1)
        ->where('users.isActive',1)
        ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
        if ($request->hasFile('image'))
        {
        $picture  = '';
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'title' => 'required|unique:books',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required'
            
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        } 
      
            $file      = $request->file('image');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His').'-'.$filename;
            //move image to public/img folder
            $file->move(public_path('img'), $picture);
       
   
            $input = $request->all();   
            $input['image'] = 'img/'.$picture;              
            $book = Book::create($input); 
            $success['name'] =  $book->title;
            return $this->sendResponse($success, 'Book create successfully.');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function search($book)
    {
        $book = DB::table('books')
        ->where('title','like','%'.$book.'%')
        ->join('users', 'users.id', '=', 'books.userId')
        ->where('books.isActive',1)
        ->where('users.isActive',1)
        ->get();
        return response()->json(['success' => $book], 200);
    }


    public function getBooksByAuthor($userId,$isAdmin)
    {
        $book = null;
        if ($isAdmin == 1) {
            $book = DB::table('books')  
            ->join('users', 'users.id', '=', 'books.userId')
            ->where('books.isActive',1) 
            ->where('users.isActive',1)
            ->select('books.*','users.name')
            ->get();
        }
        else
        {
            $book = DB::table('books')              
            ->join('users', 'users.id', '=', 'books.userId')
            ->where('books.isActive',1)
            ->where('books.userId',$userId)
            ->where('users.isActive',1)
            ->select('books.*','users.name')
            ->get();
        }
        
        return response()->json(['success' => $book], 200);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $bookId)
    {
        $validator = Validator::make($request->all(), [ 
            'title' => 'required|unique:books,title,'.$bookId,
            'description' => 'required',
            'price' => 'required', 
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
       
        $book = Book::find($bookId);
        $book->title = $request->title;
        $book->description = $request->description;
        $book->price = $request->price;
        $book->save(); 
        
        $success['title'] =  $book->title;

        return $this->sendResponse($success, 'Book update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($bookId)
    {
        $book = Book::find($bookId); 
        $book->isActive = 0;
        $book->save();
        $success['title'] =  $book->title;

        return $this->sendResponse($success, 'Book Delete successfully.');
    }
}
