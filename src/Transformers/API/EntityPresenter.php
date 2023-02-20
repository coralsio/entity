<?php

namespace Corals\Modules\Entity\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class EntityPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return EntityTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new EntityTransformer($extras);
    }
}
