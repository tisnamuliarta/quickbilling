<?php

namespace App\Services\Settings;

use IFRS\Models\Entity;

class EntityService
{
    /**
     * @param $request
     * @return array
     */
    public function index($request): array
    {
        $query = Entity::first();

        return [
            "rows" => $query,
            "status" => ($query) ? 'update' : 'insert'
        ];
    }

    /**
     * @param $form
     * @param $request
     * @param $type
     * @return array
     */
    public function formData($form, $request, $type): array
    {
        $request->request->remove('id');
        $request->request->remove('created_at');
        $request->request->remove('updated_at');
        $request->request->remove('deleted_at');
        $request->request->remove('destroyed_at');
        return $request->all();
    }
}
