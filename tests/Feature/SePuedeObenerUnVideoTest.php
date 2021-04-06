<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Video;

class SePuedeObenerUnVideoTest extends TestCase
{
    use RefreshDatabase;
    
    public function testSePuedeObenerUnVideoPorSuId() 
    {
        //crear el escenario
        //crear un video en el sistema
        //ME DEVOLVERÁ TOOS LOS CAMPOS DEL VIDEO
        /*$video = factory(Video::class)->create();

        //llamar a la api para pedir ese video
        $this->get(
            sprintf(
                '/api/videos/%s',
                $video->id
        ))->assertJsonFragment($video->toArray());*/

        //ME DEVOLVERÁ UNICAMENTE LOS CAMPOS ESPECIFICADOS
        $video = factory(Video::class)->create();
        $this->get(sprintf(
            '/api/videos/%s',
            $video->id
        ))->assertExactJson([
            'id' => $video->id,
            'titulo' => $video->titulo,
            'descripcion' => $video->descripcion,
            'url_video' => $video->url_video,
        ]);

    }
}
