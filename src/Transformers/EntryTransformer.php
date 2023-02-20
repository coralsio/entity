<?php

namespace Corals\Modules\Entity\Transformers;

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

        $transformedArray = [
            'id' => sprintf("<a href='%s'>%s</a>", $entry->getShowURL(), $entry->id),
            'entity_code' => $entry->entity->present('code'),
            'categories' => formatArrayAsLabels($entry->categories->pluck('name'), 'success', '<i class="fa fa-folder-open"></i>'),
            'tags' => formatArrayAsLabels($entry->tags->pluck('name'), 'success', '<i class="fa fa-folder-open"></i>'),
            'created_at' => format_date($entry->created_at),
            'updated_at' => format_date($entry->updated_at),
            'action' => $this->actions($entry)
        ];

        return parent::transformResponse(
            array_merge($transformedArray, EntityFacade::getDisplayableColumnsForTransformer($entity, $entry)), $entry
        );
    }
}
