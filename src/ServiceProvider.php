<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Blade;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\View;
use stuartcusackie\StatamicBladeViewData\StatamicBladeViewData;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;

class ServiceProvider extends AddonServiceProvider
{   

    public function bootAddon()
    {
        
        $this
            ->registerServices()
            ->registerViewComposers();

    }

    protected function registerServices()
    {
        $this->app->singleton('StatData', StatamicBladeViewData::class);

        return $this;
    }

    /**
     * Process the view data that has been set up
     * by Statamic. This is a bit convoluted but
     * it's efficient!
     */
    protected function registerViewComposers()
    {
        if($entry = Entry::findByUri('/' . request()->path())) {

            View::composer($entry->template(), function ($view) {
                \StatData::init($view->getData());
            });

        }
        else if($term = Term::findByUri('/' . request()->path())) {

            View::composer($term->template(), function ($view) {
                \StatData::init($view->getData());
            });

        }
        
        return $this;
    }
}
