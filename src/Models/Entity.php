<?php

namespace Corals\Modules\Entity\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Settings\Traits\DynamicFieldsModel;
use Corals\Utility\Category\Traits\ModelHasCategory;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity extends BaseModel
{
    use PresentableTrait;
    use LogsActivity;
    use ModelHasCategory;
    use DynamicFieldsModel;

    protected $table = 'entity_entities';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'entity.models.entity';

    protected $casts = [
        'properties' => 'json',
        'fields' => 'json',
    ];

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
