<?php

namespace Corals\Modules\Entity\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Search\Indexable;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Entity\Facades\EntityFacade;
use Corals\Modules\Utility\Category\Traits\ModelHasCategory;
use Corals\Modules\Utility\Rating\Traits\ReviewRateable;
use Corals\Modules\Utility\Tag\Traits\HasTags;
use Corals\Modules\Utility\Wishlist\Traits\Wishlistable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Entry extends BaseModel implements HasMedia
{
    use PresentableTrait;
    use LogsActivity;
    use InteractsWithMedia;
    use HasTags;
    use ModelHasCategory;
    use Indexable;
    use Wishlistable;
    use ReviewRateable;

    protected $table = 'entity_entries';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'entity.models.entry';

    protected $casts = [
        'properties' => 'json',
        'values' => 'json',
    ];

    protected $guarded = ['id'];

    public $galleryMediaCollection = 'entry-gallery';

    public function getIndexContent()
    {
        $titles = EntityFacade::getFullTextSearchEntityFields($this->entity, 'content');

        $columns = array_map(function ($item) {
            return 'values.' . $item['name'];
        }, $titles);

        return $this->getIndexDataFromColumns($columns);
    }

    public function getIndexTitle()
    {
        $titles = EntityFacade::getFullTextSearchEntityFields($this->entity, 'title');

        $columns = array_map(function ($item) {
            return 'values.' . $item['name'];
        }, $titles);

        return $this->getIndexDataFromColumns($columns);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function getModuleName()
    {
        return 'Entity';
    }

    /**
     * @param null $key
     * @return string
     */
    public function getIdentifier($key = null)
    {
        $identifiers = [];

        foreach (EntityFacade::getEntityIdentifiers($this->entity) as $column) {
            $identifiers[] = data_get($this->values, $column);
        }

        return join(', ', $identifiers);
    }
}
