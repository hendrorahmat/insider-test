<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMatchPerWeekRequest;
use App\Services\MatchService;
use Illuminate\Http\Request;

class PlayMatchController extends Controller
{
    /**
     * @var MatchService
     */
    private $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function store(CreateMatchPerWeekRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->matchService->playMatch($request->get('match_uuids'), $request->get('group_id'));
        return redirect()->back();
    }
}
