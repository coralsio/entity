<?php

namespace Corals\Modules\Entity\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Entity\DataTables\EntriesDataTable;
use Corals\Modules\Entity\Http\Requests\EntryRequest;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Models\Entry;
use Corals\Modules\Entity\Services\EntryService;
use Corals\Modules\Entity\Transformers\API\EntryPresenter;
use Illuminate\Http\Request;

class EntriesController extends APIBaseController
{
    protected $entryService;

    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;
        $this->entryService->setPresenter(new EntryPresenter());

        $this->corals_middleware_except = array_merge($this->corals_middleware_except, [
            'index', 'show',
        ]);

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param EntriesDataTable $dataTable
     * @return mixed
     */
    public function index(Request $request, EntriesDataTable $dataTable)
    {
        $entries = $dataTable->query(new Entry());

        return $this->entryService->index($entries, $dataTable);
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EntryRequest $request, Entity $entity)
    {
        try {
            $entry = $this->entryService->store($request, Entry::class, ['entity' => $entity]);

            return apiResponse($this->entryService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $entry->getIdentifier()]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Entity $entity, Entry $entry)
    {
        try {
            return apiResponse($this->entryService->getModelDetails($entry));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param EntryRequest $request
     * @param Entity $entity
     * @param Entry $entry
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EntryRequest $request, Entity $entity, Entry $entry)
    {
        try {
            $this->entryService->update($request, $entry);

            return apiResponse($this->entryService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $entry->getIdentifier()]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
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

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $entry->getIdentifier()]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
