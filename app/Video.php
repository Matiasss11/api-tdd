<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'titulo', 'descripcion', 'url_video',
    ];

    protected $visible = [
        'id', 'titulo', 'descripcion', 'url_video'
    ];
}
