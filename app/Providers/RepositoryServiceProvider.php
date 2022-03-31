<?php

namespace App\Providers;

use App\Repositories\ClubRepository;
use App\Repositories\Eloquent\EloquentClubRepository;
use App\Repositories\Eloquent\EloquentGroupLeagueRepository;
use App\Repositories\Eloquent\EloquentGroupMatchRepository;
use App\Repositories\Eloquent\EloquentGroupRepository;
use App\Repositories\Eloquent\EloquentLeagueRepository;
use App\Repositories\Eloquent\EloquentMatchHistoryRepository;
use App\Repositories\Eloquent\EloquentMatchRepository;
use App\Repositories\Eloquent\EloquentPotsClubRepository;
use App\Repositories\Eloquent\EloquentPotsRepository;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupMatchRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LeagueRepository;
use App\Repositories\MatchHistoryRepository;
use App\Repositories\MatchRepository;
use App\Repositories\PotsClubRepository;
use App\Repositories\PotsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LeagueRepository::class, EloquentLeagueRepository::class);
        $this->app->bind(PotsRepository::class, EloquentPotsRepository::class);
        $this->app->bind(PotsClubRepository::class, EloquentPotsClubRepository::class);
        $this->app->bind(GroupLeagueRepository::class, EloquentGroupLeagueRepository::class);
        $this->app->bind(GroupRepository::class, EloquentGroupRepository::class);
        $this->app->bind(ClubRepository::class, EloquentClubRepository::class);
        $this->app->bind(MatchRepository::class, EloquentMatchRepository::class);
        $this->app->bind(MatchHistoryRepository::class, EloquentMatchHistoryRepository::class);
        $this->app->bind(GroupMatchRepository::class, EloquentGroupMatchRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
