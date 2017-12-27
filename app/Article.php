<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'description', 'photo', 'date', 'url', 'created_at', 'updated_at'];
}
