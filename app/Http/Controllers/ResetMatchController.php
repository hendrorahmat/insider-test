<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetMatchRequest;
use App\Services\MatchService;
use Illuminate\Http\Request;

class ResetMatchController extends Controller
{
    /**
     * @var MatchService
     */
    private $matchService;

    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function store(ResetMatchRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->matchService->reset($request->get('group_id'));
        return redirect()->back();
    }
}
