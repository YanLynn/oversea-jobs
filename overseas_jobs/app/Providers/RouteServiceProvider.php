<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';
    protected $recruiterApiV1 = 'App\Http\Controllers\API\V1\Recruiter';
    protected $jobseekerApiV1 = 'App\Http\Controllers\API\V1\Jobseeker';
    protected $adminApiV1 = 'App\Http\Controllers\API\V1\Admin';
    protected $universalApiV1 = 'App\Http\Controllers\API\V1\Universal';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {        
        $this->adminRoutes();        
        $this->universalRoutes();        
        $this->recruiterRoutes();
        $this->jobseekerRoutes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v1/api.php'));
    }
    protected function recruiterRoutes()
    {
        Route::prefix('api/v1/recruiter')
             ->middleware(['api'])
             ->namespace($this->recruiterApiV1)
             ->group(base_path('routes/api/v1/recruiterApi.php'));
      
    }
    protected function jobseekerRoutes()
    {
        Route::prefix('api/v1/jobseeker')
             ->middleware(['api'])
             ->namespace($this->jobseekerApiV1)
             ->group(base_path('routes/api/v1/jobseekerApi.php'));
      
    }
    protected function adminRoutes()
    {
        Route::prefix('api/v1/admin')
             ->middleware(['api'])
             ->namespace($this->adminApiV1)
             ->group(base_path('routes/api/v1/adminApi.php'));      
    }
    protected function universalRoutes()
    {
        Route::prefix('api/v1')
             ->middleware(['api'])
             ->namespace($this->universalApiV1)
             ->group(base_path('routes/api/v1/universalApi.php'));      
    }
}
