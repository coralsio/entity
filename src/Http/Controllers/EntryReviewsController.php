<?php

namespace Corals\Modules\Entity\Http\Controllers;

use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Utility\Rating\Http\Controllers\RatingBaseController;


class EntryReviewsController extends RatingBaseController
{
    /**
     *
     */
    protected function setCommonVariables()
    {
        $this->rateableClass = Entry::class;
    }
}
