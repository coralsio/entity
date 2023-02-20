<?php

namespace Corals\Modules\Entity\Transformers\API;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Entity\Facades\EntityFacade;
use Corals\Modules\Entity\Models\Entry;

class EntryTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        parent::__construct($extras);
    }

    /**
     * @param Entry $entry
     * @return array
     * @throws \Throwable
     */
    public function transform(Entry $entry)
    {
        $entity = $entry->entity;

        foreach ($entry->getMedia($entry->galleryMediaCollection) as $media) {
            $gallery[] = [
                'id' => $media->id,
                'url' => $media->getFullUrl()
            ];
        }

        $transformedArray = [
            'id' => $entry->id,
            'entity_code' => $entry->entity->code,
            'gallery' => $gallery ?? [],
            'categories' => apiPluck($entry->categories->pluck('name', 'id')->toArray(), 'id', 'name'),
            'tags' => apiPluck($entry->tags->pluck('name', 'id')->toArray(), 'id', 'name'),
            'created_at' => format_date($entry->created_at),
            'updated_at' => format_date($entry->updated_at),
        ];

        return parent::transformResponse(
            array_merge($transformedArray, EntityFacade::getDisplayableColumnsForTransformer($entity, $entry)), $entry
        );
    }
}
