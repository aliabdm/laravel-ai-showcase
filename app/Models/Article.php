<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Ai\Concerns\HasEmbeddings;

class Article extends Model
{
    use HasEmbeddings;

    protected $withEmbeddings = ['title', 'content'];

    protected $fillable = ['title', 'content'];
}
