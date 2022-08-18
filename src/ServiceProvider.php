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
     * by Statamic. This is very convoluted but
     * it's efficient!
     */
    protected function registerViewComposers()
    {
        // Append slash to path if necessary
        $path = substr(request()->path(), 0, 1) == '/' ? request()->path() : '/' . request()->path();

        // Remove multisite url prefixes if necessary (we can't find entries by uri when they are prefixed)
        foreach(\Statamic\Facades\Site::all() as $site) {

            $sitePrefix = str_replace(request()->root(), '', $site->url);

            if(strlen($sitePrefix) && str_starts_with($path, $sitePrefix)) {
                $path = substr($path, strlen($sitePrefix));
            }

        }

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
