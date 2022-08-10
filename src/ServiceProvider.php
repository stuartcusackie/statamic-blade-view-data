<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Blade;
use Statamic\Providers\AddonServiceProvider;
use Illuminate\Support\Facades\View;
use stuartcusackie\StatamicBladeViewData\StatamicBladeViewData;
use Statamic\Facades\Entry;

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


    protected function registerViewComposers()
    {
        $views = [];

        /**
         * This may not be the most efficient way
         * espcially if we have lots of entries.
         * Can we get all templates from blueprints
         * instead??
         */
        foreach(Entry::all() as $entry) {

            if(!in_array($entry->template(), $views)) {
                $views[] = $entry->template();
            }

        }

        View::composer($views, function ($view) {
            \StatData::init($view->getData());
        });

        return $this;
    }
}
