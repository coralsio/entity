<?php

namespace Corals\Modules\Entity\Transformers\API;

use Corals\Foundation\Transformers\FractalPresenter;

class EntryPresenter extends FractalPresenter
{

    /**
     * @param array $extras
     * @return EntityTransformer|\League\Fractal\TransformerAbstract
     */
    public function getTransformer($extras = [])
    {
        return new EntryTransformer($extras);
    }
}
