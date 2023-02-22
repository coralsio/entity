<?php

namespace Corals\Modules\Entity\Transformers;

use Corals\Foundation\Transformers\FractalPresenter;

class EntityPresenter extends FractalPresenter
{
    /**
     * @return EntityTransformer
     */
    public function getTransformer($extras = [])
    {
        return new EntityTransformer($extras);
    }
}
