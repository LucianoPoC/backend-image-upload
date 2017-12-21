<?php

namespace App\Domains\Uploads\Providers;

use App\Core\Contracts\RepositoryInterface;
use App\Domains\Uploads\Controllers\DownloadsCounterController;
use App\Domains\Uploads\Controllers\UploadsController;
use App\Domains\Uploads\Controllers\ViewsCounterController;
use App\Domains\Uploads\Repositories\UploadsRepository;
use App\Domains\Uploads\Responses\UploadsResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/routes.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app
            ->when(UploadsController::class)
            ->needs(RepositoryInterface::class)
            ->give(UploadsRepository::class);

        $this->app
            ->when(ViewsCounterController::class)
            ->needs(RepositoryInterface::class)
            ->give(UploadsRepository::class);

        $this->app
            ->when(ViewsCounterController::class)
            ->needs(JsonResponse::class)
            ->give(UploadsResponse::class);

        $this->app
            ->when(DownloadsCounterController::class)
            ->needs(RepositoryInterface::class)
            ->give(UploadsRepository::class);

        $this->app
            ->when(DownloadsCounterController::class)
            ->needs(JsonResponse::class)
            ->give(UploadsResponse::class);
    }
}
