<?php

namespace Corals\Modules\Entity\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Menu\Models\Menu;
use Corals\Modules\Entity\DataTables\EntitiesDataTable;
use Corals\Modules\Entity\Http\Requests\EntityRequest;
use Corals\Modules\Entity\Models\Entity;
use Corals\Modules\Entity\Services\EntityService;

class EntitiesController extends BaseController
{
    protected $entityService;

    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;

        $this->resource_url = config('entity.models.entity.resource_url');

        $this->resource_model = new Entity();

        $this->title = trans('Entity::module.entity.title');
        $this->title_singular = trans('Entity::module.entity.title_singular');

        parent::__construct();
    }

    /**
     * @param EntityRequest $request
     * @param EntitiesDataTable $dataTable
     * @return mixed
     */
    public function index(EntityRequest $request, EntitiesDataTable $dataTable)
    {
        return $dataTable->render('Entity::entities.index');
    }

    /**
     * @param EntityRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(EntityRequest $request)
    {
        $entity = new Entity();

        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])
        ]);

        return view('Entity::entities.create_edit')->with(compact('entity'));
    }

    /**
     * @param EntityRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(EntityRequest $request)
    {
        try {
            $entity = $this->entityService->store($request, Entity::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Entity::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(EntityRequest $request, Entity $entity)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $entity->getIdentifier('code')]),
            'showModel' => $entity
        ]);

        return view('Entity::entities.show')->with(compact('entity'));
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EntityRequest $request, Entity $entity)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.update_title', ['title' => $entity->getIdentifier('code')])
        ]);

        return view('Entity::entities.create_edit')->with(compact('entity'));
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(EntityRequest $request, Entity $entity)
    {
        try {
            $this->entityService->update($request, $entity);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Entity::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param EntityRequest $request
     * @param Entity $entity
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EntityRequest $request, Entity $entity)
    {
        try {
            Menu::query()->where('key', 'entity_' . $entity->id)->delete();

            $this->entityService->destroy($request, $entity);

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])
            ];
        } catch (\Exception $exception) {
            log_exception($exception, Entity::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }
}
