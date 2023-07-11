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
