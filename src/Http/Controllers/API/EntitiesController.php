<?php

namespace Corals\Modules\Entity\Http\Controllers\API;

use Corals\Foundation\Http\Controllers\APIBaseController;
use Corals\Modules\Entity\DataTables\EntitiesDataTable;
use Corals\Modules\Entity\Http\Requests\EntityRequest;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Services\EntityService;
use Corals\Modules\Entity\Transformers\API\EntityPresenter;
use Illuminate\Http\Request;

class EntitiesController extends APIBaseController
{
    protected $entityService;

    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
        $this->entityService->setPresenter(new EntityPresenter());

        parent::__construct();
    }

    /**
     * @param Request $request
     * @param EntitiesDataTable $dataTable
     * @return mixed
     */
    public function index(Request $request, EntitiesDataTable $dataTable)
    {
        $entries = $dataTable->query(new Entity());

        return $this->entityService->index($entries, $dataTable);
    }

    /**
     * @param EntityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EntityRequest $request)
    {
        try {
            $entity = $this->entityService->store($request, Entity::class);

            return apiResponse($this->entityService->getModelDetails(), trans('Corals::messages.success.created', ['item' => $entity->getIdentifier('code')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param Entity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Entity $entity)
    {
        try {
            return apiResponse($this->entityService->getModelDetails($entity));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EntityRequest $request, Entity $entity)
    {
        try {
            $this->entityService->update($request, $entity);

            return apiResponse($this->entityService->getModelDetails(), trans('Corals::messages.success.updated', ['item' => $entity->getIdentifier('code')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EntityRequest $request, Entity $entity)
    {
        try {
            $this->entityService->destroy($request, $entity);

            return apiResponse([], trans('Corals::messages.success.deleted', ['item' => $entity->getIdentifier('code')]));
        } catch (\Exception $exception) {
            return apiExceptionResponse($exception);
        }
    }
}
