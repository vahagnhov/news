<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Http\Request;
use Mockery\Exception;
use Yajra\DataTables\DataTables;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.article');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['photo'] = null;

        if ($request->hasFile('photo')) {
            $input['photo'] = str_random(25) . str_slug(substr($input['title'], -7), '-') . "." . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('/upload/photo/'), $input['photo']);
        }

        Article::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Article Created'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $article = Article::findOrFail($id);

        $input['photo'] = $article->photo;

        if ($request->hasFile('photo')) {
            if (!$article->photo == NULL) {
                unlink(public_path('/upload/photo/' . $article->photo));
            }
            $input['photo'] = str_random(25) . str_slug(substr($input['title'], -7), '-') . "." . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('/upload/photo/'), $input['photo']);
        }

        $article->update($input);

        return response()->json([
            'success' => true,
            'message' => 'Article Updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        if (!$article->photo == NULL) {
            unlink(public_path('/upload/photo/' . $article->photo));
        }
        Article::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Article Deleted'
        ]);
    }

    public function apiArticle()
    {
        $article = Article::all();

        return Datatables::of($article)
            ->addColumn('show_photo', function ($article) {
                if ($article->photo == NULL) {
                    return 'No Image';
                }
                return '<a href="' . url($article->url) . '"><img class="rounded-square" width="99" height="67" style="padding: 2px;border: 1px solid #91a6a6;border-radius: 2px;" src="' . url('/upload/photo/' . $article->photo) . '"  alt=""></a>';
            })
            ->addColumn('action', function ($article) {
                return
                    '<a onclick="editForm(' . $article->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData(' . $article->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['show_photo', 'action'])->make(true);

    }
}