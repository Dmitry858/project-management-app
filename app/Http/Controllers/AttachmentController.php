<?php

namespace App\Http\Controllers;

use App\Services\AttachmentService;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function show($id)
    {
        $path = $this->attachmentService->getPath($id);

        return $path ? response()->file($path) : abort(404);
    }
}
