<?php

namespace Corals\Modules\Entity\Transformers;

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
        $show_url = $entity->getShowURL();

        $transformedArray = [
            'id' => $entity->id,
            'code' => sprintf('<a href="%s">%s</a>', $entity->getShowURL(), $entity->getIdentifier('code')),
            'name_singular' => HtmlElement('a', ['href' => $entity->getShowURL()], $entity->name_singular),
            'name_plural' => HtmlElement('a', ['href' => $entity->getShowURL()], $entity->name_plural),
            'has_tags' => yesNoFormatter($entity->has_tags),
            'has_gallery' => yesNoFormatter($entity->has_gallery),
            'wishlistable' => yesNoFormatter($entity->wishlistable),
            'reviewable' => yesNoFormatter($entity->reviewable),
            'category_parent' => formatArrayAsLabels($entity->categories()->pluck('name')->toArray()),
            'created_at' => format_date($entity->created_at),
            'updated_at' => format_date($entity->updated_at),
            'action' => $this->actions($entity),
        ];

        return parent::transformResponse($transformedArray);
    }
}
