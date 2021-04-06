<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Video;
use Carbon\Carbon;

class SePuedeObtenerUnListadoDeVideosTest extends TestCase
{
    use RefreshDatabase;
    
    public function testSePuedeObtenerUnListadoDeVideos() 
    {
        $this->withoutExceptionHandling();//aclara el error
        
        factory(Video::class, 2)->create();

        $this->getJson('/api/videos')
            ->assertOk()//peticion http correcta
            ->assertJsonCount(2);
    }

    public function testElPreviewDeUnVideoTieneIdYThumbnail()
    {
        //nos devuelve todo el contenido el contenido de video
        /*$videos = factory(Video::class, 2)->create();

        $this->getJson('/api/videos')
            ->assertJson($videos->toArray());*/

        //solo devolveria el id y el thumbnail
        $unId=12345;
        $unThumbnail='http://unaimagen.com';
        factory(Video::class)->create([
            'id' => $unId,
            'thumbnail' => $unThumbnail
        ]);
        
        $this->getJson('/api/videos')
            ->assertJson([
                [
                'id' => $unId,
                'thumbnail' => $unThumbnail
                ],
            ]);
    }

    public function testLosVideosEstanOrdenadosDeNuevosAMasViejos()
    {
        $videoHaceUnMes= factory(Video::class)->create([
            'created_at' => Carbon::now()->subDays(30),
        ]);
        $videoHoy= factory(Video::class)->create([
            'created_at' => Carbon::now(),
        ]);
        $videoAyer= factory(Video::class)->create([
            'created_at' => Carbon::yesterday(),
        ]);

        /*$response = */$this->getJson('/api/videos')
            ->assertJsonPath('0.id', $videoHoy->id)
            ->assertJsonPath('1.id', $videoAyer->id)
            ->assertJsonPath('2.id', $videoHaceUnMes->id);


        /*[$videoPrimero, $videoSegundo, $videoTercero] = $response->json();
        $this->assertEquals($videoHoy->id, $videoPrimero['id']);
        $this->assertEquals($videoAyer->id, $videoSegundo['id']);
        $this->assertEquals($videoHaceUnMes->id, $videoTercero['id']);*/
    }
    
    public function testSePuedeLimitarElNumeroDeVideosAObtener()
    {
        factory(Video::class, 4)->create();

        $this->getJson('/api/videos?limit=3')
            ->assertJsonCount(3);
    }

    public function testPorDefectoSoloTira30Videos()
    {
        factory(Video::class, 40)->create();

        $this->getJson('/api/videos')
            ->assertJsonCount(30);
    }

    public function providerLimitesInvalidos(): array
    {
        return[
            'El limite inferior es 1'                        => [3,'-1'],
            'No se puede obtener mas de 50 videos'           => [51,'51'],
            'No se puede pasar un limite que sea un string'  => [4,'unstring'],
        ];
    }

    /**
     * @dataProvider providerLimitesInvalidos
     **/
    public function testDevuelveUnprocessablesiHayErrorEnElLimite(int $numeroDeVideosACrear, string $limite)
    {
        factory(Video::class, $numeroDeVideosACrear)->create();

        $this->getJson(sprintf('/api/videos?limit=%s', $limite))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
