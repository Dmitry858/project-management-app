<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function show($id)
    {
        return 'Содержимое файла '.$id;
    }
}
