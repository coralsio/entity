<?php

namespace Corals\Modules\Entity\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Utility\Category\Traits\ModelHasCategory;
use Corals\Settings\Traits\DynamicFieldsModel;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity extends BaseModel
{
    use PresentableTrait, LogsActivity,
        ModelHasCategory, DynamicFieldsModel;

    protected $table = 'entity_entities';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'entity.models.entity';

    protected $casts = [
        'properties' => 'json',
        'fields' => 'json'
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
