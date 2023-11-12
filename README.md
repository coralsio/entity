# Corals Entity
Laravel Entity Builder is a revolutionary way to create objects on the fly with no development skills needed, using the entity builder view you can add different types of fields with many customizable options, once your entity is configured you will have a new menu with Listing and ready CRUD screens to manage these entities.
<p>&nbsp;</p>

<p><img src="https://www.laraship.com/wp-content/uploads/2020/10/laravel_entity_creator--770x1024.png" alt="" width="700"></p>

### Articles
- [Entity Initial Setup](#entity-initial-setup)

- [Entity Fields](#entity-fields)

<p>&nbsp;</p>

## Installation

You can install the package via composer:

```bash
composer require corals/entity
```

## Testing

```bash
vendor/bin/phpunit vendor/corals/entity/tests 
```

## Entity Initial Setup
- To define Entity you’re required to set the following items

1.<strong>Code</strong>: for internal use, use a lower case with no space

2.<strong>Name Singular</strong>: the display name of the Entity and referred with a single item

3.<strong>Name Plural</strong>: the plural reference of the displayed item.

4.<strong>Categories Parent</strong>: If you want to assign the entity entries to categories, a category dropdown will be available at Entity entry creation, Categories are managed under the Laraship utility module, only parent categories will show.

5.<strong>Has Tags</strong>: check this if you want to have the “tags” field for the entity where the entry can be assigned to different tags.

6.<strong>Has Gallery<strong>: Check this if you want to upload media to the Entry

<p>&nbsp;</p>

<p><img src="https://www.laraship.com/wp-content/uploads/2020/10/entity-definition-1024x98.png" alt=""></p>

<p>&nbsp;</p>

## Entity Fields
You can define an unlimited number of fields for each Entity, there is over 10 field types can be selected
<p>&nbsp;</p>
<p><img src="https://www.laraship.com/wp-content/uploads/2020/10/laravel_entity_fields_type-300x165.png" alt="" width="300" height="165" ></p>
<p>&nbsp;</p>

below shows the options available field definition:

- <strong>field Name</strong>: for internal use, choose a lower case name with no spaces or special characters.

- <strong>Label</strong>: this is what shows above the field when you fill this field from the entry creation screen.

- <strong>Type</strong>: once selected it cannot be changed after entity creation, some additional options will show depends on what field type is used, for example in the dropdown field, you will be asked for the data source of options which will be described below.

- <strong>List options source</strong>: you can define whether you want the data to be with manually defined options, or you want to pull the data dynamically from other models saved on the database, if you select the database to be the source, you need to select the model for example “User”, and which columns to be shown in the dropdown, for example, “email”
  
<p>&nbsp;</p>
<p><img src="https://www.laraship.com/wp-content/uploads/2020/10/dropdown_source-300x214.png" alt="" width="300" height="214">
<img src="https://www.laraship.com/wp-content/uploads/2020/10/dropdown_static-300x211.png" alt="" width="300" height="211"></p>  
<p>&nbsp;</p>

<ul>
<li><strong>Default</strong> value: initial value to be populated on the field.</li>
<li><strong>validation rules</strong>: you can have unlimited rules for example required|numeric|date| …., available options can be found in laravel documentation https://laravel.com/docs/7.x/validation#available-validation-rules</li>
</ul>

<p>&nbsp;</p>
<p><img src="https://www.laraship.com/wp-content/uploads/2020/10/entity_fields-1024x335.png" alt="" width="1024" height="335"></p>
<p>&nbsp;</p>

- <strong>Status</strong> : enable / disable field,

- <strong>Full-Text Search</strong>: do you want this field to be used for full-text search, this will add the field to the Laraship Search engine and can be used 
  for full-text search, select if you want to have it with high priority then use the checkbox title, or lower priority then use the content checkbox.

- <strong>Is Identifier</strong>: if checked this field will be referred to this object in the system, for example, the book title, or the car license plate.

- <strong>Input keys</strong>: you can add different attributes to the input for example placeholder, classes, inline styles, or any additional attributes

- <strong>Searchable</strong>: if selected this field will be available for filtering in the listing page

- <strong>Sortable</strong>: if selected this field can be used for sorting in the listing page

- <strong>Show in List</strong>: if selected this field will be shown as a column in the listing page of the entity entries

- <strong>Grid Class</strong>: this will help you in defining field size in crud and view you can see available options from bootstrap 
  https://www.w3schools.com/bootstrap4/bootstrap_grid_basic.asp.

- <strong>Order</strong>: the display order of the field in the listing table.
  
## Hire Us
Looking for a professional team to build your success and start driving your business forward.
Laraship team ready to start with you [Hire Us](https://www.laraship.com/contact)  
