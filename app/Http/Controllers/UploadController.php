<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Api\Replicate;
use App\Http\Integrations\Replicate\Requests\FetchPrediction;
use App\Http\Integrations\Replicate\Requests\Predictions;
use App\Http\Requests\UploadRequest;
use App\Services\ImageUploader;

class UploadController extends Controller
{
    public function __construct(private readonly Replicate $replicate, private readonly ImageUploader $uploader)
    {
        //
    }

    public function __invoke(UploadRequest $request)
    {
        $imageUrl = $this->uploader->uploadAndGetUrl($request->file('file'));

        $predict = new Predictions($imageUrl);

        $record = $this->replicate->send($predict)->dtoOrFail();

        $fetch = new FetchPrediction($record->imageId);

        do {
            sleep(1);

            $restoredImage = $this->replicate->send($fetch)->dtoOrFail();
        } while ($restoredImage->processing());

        return [
            'result' => $this->uploader->fetchAndUpload($restoredImage->output, 'restored'),
        ];
    }
}
