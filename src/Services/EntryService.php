<?php

namespace Corals\Modules\Entity\Services;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Category\Facades\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EntryService extends BaseServiceClass
{
    protected $excludedRequestParams = ['properties', 'categories', 'tags'];

    /**
     * @param $request
     * @param $modelClass
     * @param array $additionalData
     * @return mixed|void
     */
    public function store($request, $modelClass, $additionalData = [])
    {
        $entity = Arr::get($additionalData, 'entity');

        $values = $request->get('properties');

        $entry = $entity->entries()->create([
            'values' => $values
        ]);

        $this->handleUploadFiles($entry, $values);

        $entry->update([
            'values' => $values
        ]);

        $this->model = $entry;
        $this->postStoreUpdate($request, $additionalData);

        return $this->model;
    }

    /**
     * @param $request
     * @param array $additionalData
     */
    public function postStoreUpdate($request, $additionalData = [])
    {
        $this->model->attachTags($request->get('tags', []));

        $this->model->categories()->sync($request->get('categories', []));

        Category::setModelOptions($request, $this->model);
    }

    /**
     * @param $request
     * @param $entry
     * @param array $additionalData
     * @return mixed|void
     */
    public function update($request, $entry, $additionalData = [])
    {
        $values = $request->get('properties');

        $this->handleUploadFiles($entry, $values);

        $entry->update([
            'values' => $values
        ]);

        $this->model = $entry;

        $this->postStoreUpdate($request, $additionalData);
    }

    /**
     * @param $entry
     * @param $values
     */
    protected function handleUploadFiles($entry, &$values): void
    {
        foreach (request()->files as $files) {
            foreach ($files as $propName => $file) {

                if ($oldMediaId = $entry->getProperty($propName, null, null, 'values')) {
                    Media::query()->where('id', $oldMediaId)->delete();
                }

                $mediaObject = $this->uploadFile($entry->entity, $entry, $file, $propName);
                $values[$propName] = $mediaObject->id;
            }
        }
    }

    /**
     * @param $entity
     * @param $entry
     * @param $file
     * @param $propName
     * @return mixed
     */
    protected function uploadFile($entity, $entry, $file, $propName)
    {
        $prefix = Str::slug(class_basename($entry));
        $root = "$prefix/$entity->code/{$entry->id}/files";

        $collectionName = sprintf("%s-%s-%s", $entity->code, $prefix, $propName);

        return $entry->addMedia($file)
            ->withCustomProperties(['root' => $root])
            ->toMediaCollection($collectionName);
    }
}
