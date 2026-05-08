<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use League\Flysystem\UnableToRetrieveMetadata;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof UnableToRetrieveMetadata) {
            $message = 'Uploaded temporary file is missing or expired. Please re-upload the file and try again.';

            // If this is an AJAX/Livewire request, return JSON validation-like response
            if ($request->expectsJson() || $request->header('X-Livewire') !== null || $request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'message' => $message,
                    'errors' => [
                        'images' => [$message],
                        'primary_image' => [$message],
                    ],
                ], 422);
            }

            // Otherwise redirect back with an error
            return redirect()->back()->withErrors(['images' => $message])->withInput();
        }

        return parent::render($request, $e);
    }
}
