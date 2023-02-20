<?php

namespace Corals\Modules\Entity\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\Entity\DataTables\EntriesDataTable;
use Corals\Modules\Entity\Http\Requests\EntryRequest;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Entity\Services\EntryService;

class EntriesController extends BaseController
{
    protected $entryService;

    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;

        $this->resource_url = route(
            config('entity.models.entry.resource_route'),
            ['entity' => request()->route('entity') ?: '_']
        );

        $this->title = trans('Entity::module.entry.title');
        $this->title_singular = trans('Entity::module.entry.title_singular');

        parent::__construct();
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param EntriesDataTable $dataTable
     * @return mixed
     */
    public function index(EntryRequest $request, Entity $entity, EntriesDataTable $dataTable)
    {
        $this->setViewSharedData([
            'title' => trans('Entity::module.entry.entry_title', ['entity' => $entity->name_plural]),
        ]);

        return $dataTable->render('Entity::entries.index', compact('entity'));
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(EntryRequest $request, Entity $entity)
    {
        $entry = new Entry();

        $this->setViewSharedData([
            'title' => trans('Entity::module.entry.entry_title', ['entity' => $entity->name_plural]),
            'title_singular' => trans('Corals::labels.create_title', ['title' => $entity->name_singular]),
        ]);

        return view('Entity::entries.create_edit')->with(compact('entry', 'entity'));
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function store(EntryRequest $request, Entity $entity)
    {
        try {
            $entry = $this->entryService->store($request, Entry::class, ['entity' => $entity]);

            flash(trans('Corals::messages.success.created', ['item' => $entry->getIdentifier()]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Entry::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(EntryRequest $request, Entity $entity, Entry $entry)
    {
        $this->setViewSharedData([
            'title' => trans('Entity::module.entry.entry_title', ['entity' => $entity->name_plural]),
            'title_singular' => trans('Corals::labels.show_title', ['title' => $entry->getIdentifier()]),
            'showModel' => $entry,
        ]);

        return view('Entity::entries.show')->with(compact('entry', 'entity'));
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EntryRequest $request, Entity $entity, Entry $entry)
    {
        $this->setViewSharedData([
            'title' => trans('Entity::module.entry.entry_title', ['entity' => $entity->name_plural]),
            'title_singular' => trans('Corals::labels.update_title', ['title' => $entry->getIdentifier()]),
        ]);

        return view('Entity::entries.create_edit')->with(compact('entity', 'entry'));
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function update(EntryRequest $request, Entity $entity, Entry $entry)
    {
        try {
            $this->entryService->update($request, $entry);

            flash(trans('Corals::messages.success.updated', ['item' => $entry->getIdentifier()]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Entry::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EntryRequest $request, Entity $entity, Entry $entry)
    {
        try {
            $this->entryService->destroy($request, $entry);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $entry->getIdentifier()]),
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Entry::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
