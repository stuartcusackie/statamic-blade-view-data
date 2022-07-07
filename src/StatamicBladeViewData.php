<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Cache;

class StatamicBladeViewData {

    public $context;
    public $page;
    public $site;
    public $globalSets = [];

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function init(array $viewData) {
        $this->context = $viewData;
        $this->page = $viewData['page'];
        $this->site = $viewData['site'];
        $this->globalSets = $this->initGlobalSets($viewData);
    }

    /**
     * Return the laravel variable
     * that contains all view data
     * usually named $__data.
     */
    public function context() {
        return $this->context;
    }

    public function page() {
        return $this->page;
    }

    public function site() {
        return $this->site;
    }

    public function globalSet(string $handle) {

        // First try to get the global set from existing data
        if(array_key_exists($handle, $this->globalSets)){

            var_dump($this->globalSets[$handle]);
            die();

            return $this->globalSets[$handle];
        }

        // If the view was not correctly added to the config or if
        // this is a non-entry page then we will have to retrive manually.
        $globalSet = \Statamic\Facades\Globalset::findByHandle($handle);

        if(!$globalSet){
            throw new \Exception('A global set with this handle does not exist: ' . $handle);
        }

        return $globalSet->inCurrentSite();
    }

    protected function initGlobalSets($viewData) {

        return array_filter($viewData, function($v, $k) {
            return is_object($v) && get_class($v) == 'Statamic\Globals\Variables';
        }, ARRAY_FILTER_USE_BOTH);

    }
}
