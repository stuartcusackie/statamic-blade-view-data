<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Blade;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\View;
use stuartcusackie\StatamicBladeViewData\StatamicBladeViewData;

class ServiceProvider extends AddonServiceProvider
{   

    public function register()
    {
        

    }

    public function bootAddon()
    {
        $this->app->singleton('StatData', StatamicBladeViewData::class);
        $this->registerViewComposers();

    }


    protected function registerViewComposers()
    {
        // Could probably just queries all collections and their views automatically?
        View::composer(config('statamic-blade-view-data.views'), function ($view) {

            \StatData::init($view->getData());
            
            // This doesn't work
            // $thing = $this->app->make('StatData');
            // $thing->init($view->getData());
            
            // Do we need this? Probably not.
            // $view->with($view->getData()));
        });

        return $this;
    }
}