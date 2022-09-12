<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::all();
        return $this->success([
            'data' => $tags
        ]);
    }
}
