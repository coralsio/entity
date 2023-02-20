<?php

namespace Corals\Modules\Entity\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Entity\Facades\EntityFacade;
use Corals\Modules\Entity\Models\Entry;
use Illuminate\Validation\ValidationException;

class EntryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Entry::class);

        return $this->isAuthorized();
    }

    /**
     * @return array
     * @throws ValidationException
     */
    public function rules()
    {
        $this->setModel(Entry::class);

        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $entity = EntityFacade::normalizeEntityModel($this->route('entity'));

            $rules = array_merge($rules, $this->getCustomFieldsRules($entity->fields));
        }
        return $rules;
    }

}
