<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Blade;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\View;

class ServiceProvider extends AddonServiceProvider
{   

    public function bootAddon()
    {
        $this->registerViewComposers();
    }
    
    protected function registerViewComposers(): self
    {
        // Could probably just queries all collections and their views automatically?
        View::composer(config('statamic-blade.view-data.views'), function ($view) {

            $viewData = $view->getData();

            $this->app->when('StatamicViewData')
                ->needs('$viewData')
                ->give($viewData);
            
            $this->app->singleton(StatamicViewData::class, function ($app) use ($viewData) {
                return new StatamicViewData($viewData);
            });

            // Do we need this? Probably not.
            // $view->with($viewData);
        });
    }
}