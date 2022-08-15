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
     * Extract the Statamic template data for the
     * current entry and pass it to our singleton.
     */
    protected function registerViewComposers()
    {
        // Append slash to path if necessary
        $path = substr(request()->path(), 0, 1) == '/' ? request()->path() : '/' . request()->path();
        $template = null;

        if($entry = Entry::findByUri($path)) {
            $template = $entry->template();
        }
        else if($term = Term::findByUri($path)) {
            $template = $term->template();
        }

        if($template) {

            View::composer($template, function ($view) {
                \StatData::init($view->getData());
            });

        }
        
        return $this;
    }
}
