<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StageService;
use App\Http\Requests\StoreStageRequest;

class StageController extends Controller
{
    protected $stageService;

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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
