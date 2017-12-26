<?php

namespace Tests\Feature;

use App\Domains\Uploads\Models\Uploads;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadsTest extends TestCase
{
    use DatabaseTransactions;

    private $endpoint = 'http://api.app.local/v1/uploads/';

    private $imageResponseEntity = [
        'link',
        'title',
        'downloads',
        'views',
        'created_at',
        'updated_at',
    ];

    /**
     * @param bool $title
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function callPostImage(bool $title = false, $parameters = []): \Illuminate\Foundation\Testing\TestResponse
    {
        $response = $this->call(
            'POST',
            $this->endpoint,
            $title ? ['title' => 'The title here'] : $parameters,
            [],
            [
                'file' => $this->prepareFileUpload(__DIR__.'/image.jpg'),
            ],
            []
        );

        return $response;
    }

    protected function prepareFileUpload($path, $filename = 'image.jpg')
    {
        $this->assertFileExists($path);

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

        $mime = finfo_file($fileInfo, $path);

        return new UploadedFile($path, $filename, $mime, null, null, true);
    }

    public function testGetAllUploadsSuccessfully()
    {
        Uploads::truncate();

        $this->callPostImage();
        $this->callPostImage();
        $this->callPostImage();

        $response = $this->get($this->endpoint);

        $this->assertNotEmpty($response);

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $response->assertSee('meta');
    }

    public function testGetAllUploadsWithoutResources()
    {
        Uploads::truncate();

        $response = $this->get($this->endpoint);

        $this->assertNotEmpty($response);

        $response->assertStatus(404);

        $response->assertSee('status_code');

        $response->assertSee('message');

        $response->assertSee('code');
    }

    public function testPostAImageWithTitleSuccessfully()
    {
        $response = $this->callPostImage(true);

        $this->assertNotEmpty($response);

        foreach ($this->imageResponseEntity as $value) {
            $response->assertSee($value);
        }

        $response->assertStatus(201);
    }

    public function testPostAImageWithoutTitleSuccessfully()
    {
        $response = $this->callPostImage();

        $this->assertNotEmpty($response);

        foreach ($this->imageResponseEntity as $value) {
            if ($value == 'title') {
                $response->assertDontSee($value);

                continue;
            }

            $response->assertSee($value);
        }

        $response->assertStatus(201);
    }

    public function testGetAImageById()
    {
        $responseInsert = $this->callPostImage();

        $id = json_decode($responseInsert->getContent())->data[0]->id;

        $response = $this->get($this->endpoint.$id);

        $this->assertNotEmpty($response);

        $this->assertEquals($responseInsert->getContent(), $response->getContent());

        $response->assertStatus(200);
    }

    public function testGetAImageByIdThatDoesNotExist()
    {
        Uploads::destroy(1);

        $response = $this->get($this->endpoint. 1);

        $this->assertNotEmpty($response);

        $response->assertStatus(404);
    }

    public function testShouldIgnoreAttributesThatDoesNotMatchesWithEntity()
    {
        $response = $this->callPostImage(false, [
           'foo' => 'bar',
        ]);

        $response->assertStatus(201);

        $response->assertDontSee('foo');
    }

    public function testIncrementViews()
    {
        $responseInsert = $this->callPostImage();

        $uri = $this->endpoint.json_decode($responseInsert->getContent())->data[0]->id.'/views';

        $response = $this->put($uri);

        $response->assertStatus(200);

        $response->assertJsonFragment(['views' => 1]);

        $response = $this->put($uri);

        $response->assertJsonFragment(['views' => 2]);
    }

    public function testIncrementDownloads()
    {
        $responseInsert = $this->callPostImage();

        $uri = $this->endpoint.json_decode($responseInsert->getContent())->data[0]->id.'/downloads';

        $response = $this->put($uri);

        $response->assertStatus(200);

        $response->assertJsonFragment(['downloads' => 1]);

        $response = $this->put($uri);

        $response->assertJsonFragment(['downloads' => 2]);
    }
}
