<?php

namespace Corals\Modules\Entity\Transformers\API;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Entity\Models\Entity;

class EntityTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('entity.models.entity.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Entity $entity
     * @return array
     * @throws \Throwable
     */
    public function transform(Entity $entity)
    {

        $transformedArray = [
            'id' => $entity->id,
            'code' => $entity->getIdentifier('code'),
            'name_singular' => $entity->name_singular,
            'name_plural' => $entity->name_plural,
            'has_tags' => $entity->has_tags,
            'has_gallery' => $entity->has_gallery,
            'wishlistable' => $entity->wishlistable,
            'reviewable' => $entity->reviewable,
            'category_parent' => apiPluck($entity->categories()->pluck('name', 'id')->toArray(), 'id', 'name'),
            'created_at' => format_date($entity->created_at),
            'updated_at' => format_date($entity->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
