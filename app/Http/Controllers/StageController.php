<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StageService;
use App\Http\Requests\StoreStageRequest;
use App\Http\Requests\UpdateStageRequest;

class StageController extends Controller
{
    protected StageService $stageService;

    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $title = __('titles.stages_index');
        $stages = $this->stageService->getList();

        return view('stages.index', compact('title', 'stages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $title = __('titles.stages_create');

        return view('stages.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreStageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreStageRequest $request)
    {
        $result = $this->stageService->create($request->all());

        return redirect()->route('stages.index')->with($result['status'], $result['text']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $stage = $this->stageService->get($id);
        $title = __('titles.stages_edit', ['name' => $stage->name]);

        return view('stages.edit', compact('title', 'stage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStageRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateStageRequest $request, $id)
    {
        $result = $this->stageService->update($id, $request->all());

        return redirect()->route('stages.index')->with($result['status'], $result['text']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $result = $this->stageService->delete([$id]);

        return redirect()->route('stages.index')->with($result['status'], $result['text']);
    }

    /**
     * Remove the group of specified resources from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyGroup(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->stageService->delete($data);
        $request->session()->flash($result['status'], $result['text']);

        return response()->json($result);
    }
}
