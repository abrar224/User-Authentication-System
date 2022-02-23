<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use DataTables;
use Session;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::get();
        if($request->ajax()){
            $allData = DataTables::of($posts)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn='<a href="javascript:void(0)" data-toogle="tooltip" data-id="'.
                $row->id.'"data-original-title="Edit" class="edit btn btn-primary btn-sm editpost">Edit</a>';
                $btn.='<a href="javascript:void(0)" data-toogle="tooltip" data-id="'.
                $row->id.'"data-original-title="Delete" class="m-1 edit btn btn-danger btn-sm deletepost">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
            return $allData;
        }
        return view('posts', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $post = new Post();
        $post->id = $request->post_id;
        $post->username = Session::get('loginuser');
        $post->description = $request->description;
        $res2 = $post->save();
        if($res2){
            return response()->json(['success'=>'Post Added Successfuly']);
        }
        else{
            return response()->json(['fail'=>'Something Wrong']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
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
        $data = Post::find($id);
        $data->delete();
        return response()->json(['success'=>'Post Deleted Successfuly']);
    }
}
