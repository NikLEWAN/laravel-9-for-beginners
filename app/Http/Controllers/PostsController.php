<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostFormRequest;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('blog.index', [
            'posts' =>  Post::orderBy('updated_at', 'desc')->paginate(20)
            //'posts' =>  Post::orderBy('updated_at', 'desc')->get()
        ]);
    }

    /**`
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(PostFormRequest $request)
    {
        // dd($request->all());

        // OOP
        // $post = new Post();
        // $post->title = $request->title;
        // $post->excerpt = $request->excerpt;
        // $post->body = $request->body;
        // $post->image_path = 'temporary';
        // $post->is_published = $request->is_published === 'on';
        // $post->min_to_read = $request->min_to_read;
        // $post->save();

        //Validation 
        $request->validated();

        // Eloquent
        Post::create([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => $this->storeImage($request),
            'is_published' => $request->is_published === 'on',
            'min_to_read' => $request->min_to_read
        ]);

        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @return Application|Factory|View
     * Optional route parameter needs to have a default value eg: $id = 1
     */
    public function show($id)
    {
        return view('blog.show', [
            'post' => Post::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('blog.edit', [
            'post' => Post::where('id', $id)->first()
        ]);
        //$post = Post::where('id', $id)->get();

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PostFormRequest $request, $id)
    {
        $request->validated();

        if($request->hasFile('image_path')){
            $request->merge([
                'image_path' => $this->storeImage($request)
            ]);
        }

        Post::where('id', $id)->update($request->except([
            '_token', '_method'
        ]));

        return redirect()->route('blog.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Post::destroy($id);
        return redirect()->route('blog.index')->with('message', 'Post deleted successfully');
    }
    private function storeImage($request)
    {
        if ($request->hasFile('image')) {
            // $request->validate([
            //     'image' => 'mimes:jpeg,png|max:5048'
            // ]);

            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $name);

            return $name;
        }
    }
}
