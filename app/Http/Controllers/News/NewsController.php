<?php

namespace App\Http\Controllers\News;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function view(){
        $articles = DB::table('articles')->orderBy('id', 'desc')->paginate(12);
        //$articles = Article::get()->toArray();
        return view('news.view')->with('articles', $articles);
    }
}
