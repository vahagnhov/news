@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="starter-template">
            <div class="row">
                @if(count($articles)>0)
                    @foreach($articles as $article)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <a href="{{$article->url}}">
                                <div class="thumbnail" id="thumb">
                                    <img alt="{{$article->title}}" src="/upload/photo/{{$article->photo}}"
                                         class="news-pic">

                                    <div class="caption">
                                        <a href="{{$article->url}}"><h5>{{$article->title}}</h5></a>

                                        <p>{{$article->description}}</p>

                                        <p class="nl-dates">{{ date("d F Y",strtotime($article->date)) }}
                                            at {{date("g:ha",strtotime($article->date)) }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                    {{ $articles->links() }}
                @else
                    <div class="alert alert-info">
                        <strong>There is no news yet!</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /.container -->
@endsection
