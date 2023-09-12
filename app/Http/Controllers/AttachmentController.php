<?php

namespace App\Http\Controllers;

use App\Services\AttachmentService;

class AttachmentController extends Controller
{
    protected AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Contracts\View\View
     */
    public function show(int $id)
    {
        $path = $this->attachmentService->getPath($id);

        return $path ? response()->file($path) : abort(404);
    }
}
