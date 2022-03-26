<?php

namespace App\Http\Controllers;

use App\Repositories\LeagueRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @var LeagueRepository
     */
    private $leagueRepository;

    public function __construct(LeagueRepository $leagueRepository)
    {
        $this->leagueRepository = $leagueRepository;
    }

    public function index()
    {
        $datas = $this->leagueRepository->findLeague();
        return view('dashboard', compact('datas'));
    }
}
