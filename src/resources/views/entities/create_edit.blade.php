@extends('layouts.crud.create_edit')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot
        @slot('breadcrumb')
            {{ Breadcrumbs::render('entity_entity_create_edit') }}
        @endslot
    @endcomponent
@endsection
@section('css')
    <style>
        .custom-field-form {
            padding: 10px;
        }

        .custom-field-form:nth-child(odd) {
            background-color: #f9f9f9;
        }
    </style>
@endsection
@section('content')
    @parent
    <div class="row">
        <div class="col-md-12">
            @component('components.box')
                {!! CoralsForm::openForm($entity) !!}
                <div class="row">
                    <div class="col-md-3">
                        {!! CoralsForm::text('code','Entity::attributes.entity.code', true, null, $entity->exists?['readonly']:[]) !!}
                    </div>
                    <div class="col-md-3">
                        {!! CoralsForm::text('name_singular','Entity::attributes.entity.name_singular', true, null, []) !!}
                    </div>

                    <div class="col-md-3">
                        {!! CoralsForm::text('name_plural','Entity::attributes.entity.name_plural', true, null, []) !!}
                    </div>

                    <div class="col-md-3">
                        {!! CoralsForm::select('categories','Entity::attributes.entity.categories_parent', \Category::getCategoriesList(null,true),false,null,['multiple'=>false], 'select2') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {!! CoralsForm::checkbox('has_tags', 'Entity::attributes.entity.has_tags',$entity->has_tags) !!}
                    </div>
                    <div class="col-md-3">
                        {!! CoralsForm::checkbox('has_gallery', 'Entity::attributes.entity.has_gallery',$entity->has_gallery) !!}
                    </div>

                    <div class="col-md-3">
                        {!! CoralsForm::checkbox('reviewable', 'Entity::attributes.entity.reviewable',$entity->reviewable) !!}
                    </div>

                    <div class="col-md-3">
                        {!! CoralsForm::checkbox('wishlistable', 'Entity::attributes.entity.wishlistable',$entity->wishlistable) !!}
                    </div>

                </div>
                <h4>Fields</h4>
                <div class="form-group">
                    <span data-name="identifier"></span>
                </div>
                <hr/>
                @forelse(\CustomFields::getSortedFields($entity)  ?? [] as $index => $field)
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=> $index,'field'=>$field,'has_field_config'=>true])
                @empty
                    @include('Settings::custom_fields.partials.custom_fields_form',['index'=>0,'field'=>[],'has_field_config'=>true])
                @endforelse

                @include('Settings::custom_fields.partials.new_custom_field_btn',['has_field_config'=>true])


                {!! CoralsForm::customFields($entity) !!}

                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm($entity) !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')

    <script>

        $(document).on('change', '.is_identifier', function () {
            let isChecked = $(this).prop('checked');

            if (isChecked) {

                let formIndex = $(this).data('form_index');

                $($(`[name='fields[${formIndex}][field_config][full_text_search][]']`)[0])
                    .prop('checked', true)
                    .trigger('change');

                $(`[name='fields[${formIndex}][field_config][searchable]']`)
                    .prop('checked', true)
                    .trigger('change');

                $(`[name='fields[${formIndex}][field_config][show_in_list]']`)
                    .prop('checked', true)
                    .trigger('change');
            }

        });


    </script>
@endsection
