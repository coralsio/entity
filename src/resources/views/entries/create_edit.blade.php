@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('entity_entry_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a href="#fields"
                           class="nav-link active"
                           data-toggle="tab">@lang("Entity::labels.entry.entity_fields",['entity'=>$entity->name_singular])</a>
                    </li>
                    @if($entity->has_gallery && $entry->exists)
                        <li class="nav-item">
                            <a href="#gallery-field" class="nav-link"
                               data-toggle="tab">@lang("Entity::labels.entry.gallery")</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="fields">
                        {!! CoralsForm::openForm($entry, ['files'=>true]) !!}
                        {!! CoralsForm::customFields($entry,'col-md-4',$entity->fields,'values') !!}

                        @if($entity->has_tags || $entity->categories()->first())
                            <div class="row">
                                @if($entity->has_tags)
                                    <div class="col-md-4">
                                        {!! CoralsForm::select('tags[]','Entity::attributes.entry.tags', \Tag::getTagsList(),false,null,['class'=>'tags', 'multiple'=>true], 'select2') !!}
                                    </div>
                                @endif

                                @if($parentCategory = $entity->categories()->first())
                                    <div class="col-md-4">
                                        {!! CoralsForm::select('categories[]','Entity::attributes.entry.categories', \Category::getCategoriesByParent($parentCategory),false,null,['multiple'=>true,'id'=>'categories'], 'select2') !!}
                                    </div>

                                    <div class="col-md-4" id="attributes">

                                    </div>
                                @endif
                            </div>
                        @endif
                        {!! CoralsForm::formButtons() !!}
                        {!! CoralsForm::closeForm($entry) !!}
                    </div>
                    @if($entity->has_gallery && $entry->exists)
                        <div class="tab-pane" id="gallery-field">
                            @include('Utility::gallery.gallery',['galleryModel'=>$entry,'editable'=>true])
                        </div>
                    @endif
                </div>
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    @include('utility-category::category_scripts', ['category_field_id'=>'#categories','attributes_div'=>'#attributes','product'=>$entry])
@endsection
