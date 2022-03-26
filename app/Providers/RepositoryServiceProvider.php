<?php

namespace App\Providers;

use App\Repositories\Eloquent\EloquentGroupLeagueRepository;
use App\Repositories\Eloquent\EloquentGroupRepository;
use App\Repositories\Eloquent\EloquentLeagueRepository;
use App\Repositories\Eloquent\EloquentPotsClubRepository;
use App\Repositories\Eloquent\EloquentPotsRepository;
use App\Repositories\GroupLeagueRepository;
use App\Repositories\GroupRepository;
use App\Repositories\LeagueRepository;
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
