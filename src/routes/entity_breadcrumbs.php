<?php

//Entity
Breadcrumbs::register('entity_entities', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('Entity::module.entity.title'), url(config('entity.models.entity.resource_url')));
});

Breadcrumbs::register('entity_entity_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('entity_entities');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('entity_entity_show', function ($breadcrumbs) {
    $breadcrumbs->parent('entity_entities');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Entry

Breadcrumbs::register('entity_entries', function ($breadcrumbs) {
    $breadcrumbs->parent('entity_entities');

    $entity = request()->route('entity');

    $breadcrumbs->push(view()->shared('title'),
        route(config('entity.models.entry.resource_route'), ['entity' => $entity->hashed_id]));
});

Breadcrumbs::register('entity_entry_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('entity_entries');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('entity_entry_show', function ($breadcrumbs) {
    $breadcrumbs->parent('entity_entries');
    $breadcrumbs->push(view()->shared('title_singular'));
});
