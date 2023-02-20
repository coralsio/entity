@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('entity_entity_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box')
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive entry-table">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>@lang('Entity::attributes.entity.code')</td>
                            <td>{!! $entity->presentStripTags('code') !!}</td>
                        </tr>
                        <tr>
                            <td>@lang('Entity::attributes.entity.name_plural')</td>
                            <td>{!! $entity->presentStripTags('name_plural') !!}</td>
                        </tr>
                        <tr>
                            <td>@lang('Entity::attributes.entity.name_singular')</td>
                            <td>{!! $entity->presentStripTags('name_singular') !!}</td>
                        </tr>
                        <tr>
                            <td>@lang('Entity::attributes.entity.has_tags')</td>
                            <td>{!! $entity->present('has_tags') !!}</td>
                        </tr>

                        <tr>
                            <td>@lang('Entity::attributes.entity.has_gallery')</td>
                            <td>{!! $entity->present('has_gallery') !!}</td>
                        </tr>

                        <tr>
                            <td>@lang('Entity::attributes.entity.wishlistable')</td>
                            <td>{!! $entity->present('wishlistable') !!}</td>
                        </tr>

                        <tr>
                            <td>@lang('Entity::attributes.entity.reviewable')</td>
                            <td>{!! $entity->present('reviewable') !!}</td>
                        </tr>

                        <tr>
                            <td>@lang('Entity::attributes.entity.categories_parent')</td>
                            <td>{!! $entity->present('category_parent') !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcomponent
@endsection

