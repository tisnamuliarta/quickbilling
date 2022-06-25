<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\ItemGroup;
use Illuminate\Http\Request;

class ItemGroupController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $row = ItemGroup::all();

        return $this->success([
            'rows' => $row,
        ]);
    }
}
