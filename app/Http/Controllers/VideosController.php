<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ListadoDeVideosRequest;
use App\Video;
use App\Dtos\VideoPreview;

class VideosController extends Controller
{
    public function index(ListadoDeVideosRequest $request)
    {
        return Video::limit($request->getLimit())
            ->orderBy('created_at', 'DESC')
            ->get()
            ->mapInto(VideoPreview::class);
    }
    
    public function get(Video $video)
    {
        return $video;
    }
}
