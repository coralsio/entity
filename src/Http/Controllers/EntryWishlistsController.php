<?php

namespace Corals\Modules\Entity\Http\Controllers;

use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Utility\Wishlist\Http\Controllers\WishlistBaseController;


class EntryWishlistsController extends WishlistBaseController
{
    /**
     *
     */
    protected function setCommonVariables()
    {
        $this->wishlistableClass = Entry::class;
    }

    /**
     *
     */
    public function setTheme()
    {
        \Theme::set(\Settings::get('active_frontend_theme', config('themes.corals_frontend')));
    }
}
