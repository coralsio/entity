<?php

return [
    'models' => [
        'entity' => [
            'presenter' => \Corals\Modules\Entity\Transformers\EntityPresenter::class,
            'resource_url' => 'entity/entities',
            'actions' => [
                'entries' => [
                    'icon' => 'fa fa-fw fa-th',
                    'class' => 'btn btn-secondary btn-default btn-sm',
                    'href_pattern' => ['pattern' => '[arg]/entries', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return trans('Entity::module.entry.title');"],
                    ],
                    'data' => [],
                ],
                'createEntry' => [
                    'class' => 'btn btn-success btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/entries/create',
                        'replace' => ['return $object->getShowUrl();'],
                    ],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ["return  trans('Corals::labels.create') .' ' . trans('Entity::module.entry.title_singular');"],
                    ],
                    'data' => [],
                ],
            ],
        ],

        'entry' => [
            'presenter' => \Corals\Modules\Entity\Transformers\EntryPresenter::class,
            'resource_route' => 'entity.entities.entries.index',
            'resource_relation' => 'entity',
            'relation' => 'entry',

        ],
    ],
];
