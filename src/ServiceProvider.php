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
     * Attempt to find an entry of term from the
     * current url and extract the data from it's
     * template view.
     * Not ideal but it works!
     */
    protected function registerViewComposers()
    {
        $template = null;
        $uri = '/' . request()->path();
        $sitePrefix = \Statamic\Facades\Site::current()->url;

        if(str_starts_with($uri, $sitePrefix)) {
            $uri = substr($uri, strlen($sitePrefix));
        }

        // findByUri requires leading slashes
        $uri = str_starts_with($uri, '/') ? $uri : '/' . $uri;
       
        if($entry = Entry::findByUri($uri)) {
            $template = $entry->template();
        }
        else if($term = Term::findByUri($uri)) {
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
