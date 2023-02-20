@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('entity_entry_show') }}
        @endslot
    @endcomponent
@endsection

@section('content')


    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a href="#fields"
                           class="nav-link active"
                           data-toggle="tab">@lang("Entity::labels.entry.entry_fields",['entry'=>$entry->getIdentifier()])</a>
                    </li>
                    @if($entity->has_gallery && $entry->exists)
                        <li>
                            <a href="#gallery-field" data-toggle="tab">@lang("Entity::labels.entry.gallery")</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="fields">

                        <div class="row">
                            @if($entity->reviewable)
                                <div class="col-md-1">
                                    {!! CoralsForm::link(url("utilities/ratings"),'Entity::attributes.entry.reviews') !!} {{$entry->countRating()}}
                                </div>
                            @endif

                            @if($entity->wishlistable)
                                <div class="col-md-1">
                                    @lang('Entity::attributes.entry.wishlists') [{{$entry->wishlistsCount()}}]
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! EntityFacade::renderEntryFieldsValues($entry,$entity) !!}
                            </div>
                        </div>
                    </div>
                    @if($entity->has_gallery)
                        <div class="tab-pane" id="gallery-field">
                            @include('Utility::gallery.gallery',['galleryModel'=>$entry,'editable'=>false])
                        </div>
                    @endif
                </div>
            @endcomponent
        </div>
    </div>


@endsection

