<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader; // servis koji registrira alias
use Illuminate\Support\ServiceProvider;
use App\Services\CommentsService;

class CommentServiceProvider extends ServiceProvider
{
	 /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
	
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        AliasLoader::getInstance()->alias('Comments','App\Facades\Comments');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['comments'] = $this->app->share(function($app) {
			return new CommentsService($app->view);
		});
		// 'comments' - ime koje smo returnali u fasadi; 
		// u app prima sve servisa aplikacije
    }
	
	/**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [CommentsService::class];
    }
}
