<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Api\Replicate;
use App\Http\Integrations\Replicate\Requests\FetchPrediction;
use App\Http\Integrations\Replicate\Requests\Predictions;
use App\Http\Requests\UploadRequest;
use App\Services\ImageUploader;
use Illuminate\Support\Facades\Pipeline;

class UploadController extends Controller
{
    public function __construct(private readonly Replicate $replicate, private readonly ImageUploader $uploader)
    {
        //
    }

    public function __invoke(UploadRequest $request)
    {
        $imageUrl = $this->uploader->uploadAndGetUrl($request->file('file'));

        $outputImageUrl = Pipeline::send($imageUrl)
            ->through([
                $this->initiatePrediction(...),
                $this->checkCompleteness(...),
                $this->uploadRestored(...),
            ])
            ->then(fn($imageUrl) => $imageUrl);

        return [
            'result' => $outputImageUrl,
        ];
    }

    private function initiatePrediction(string $imageUrl, $next)
    {
        $predict = new Predictions($imageUrl);
        $record = $this->replicate->send($predict)->dtoOrFail();

        return $next($record->imageId);
    }

    private function checkCompleteness(string $imageId, $next)
    {
        $fetch = new FetchPrediction($imageId);

        do {
            sleep(1);

            $restoredImage = $this->replicate->send($fetch)->dtoOrFail();
        } while ($restoredImage->processing());

        return $next($restoredImage->output);
    }

    private function uploadRestored(string $imageUrl, $next)
    {
        return $next($this->uploader->fetchAndUpload($imageUrl, 'restored'));
    }
}
