<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Cache;
use Statamic\Facades\GlobalSet;

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
    
    /**
     * Retrieve a global set from the view data
     * but fallback to a Statamic query in
     * case we can't find it or is an non-entry / error page.
     *
     * @param string $handle
     * @return set
     */
    public function globalSet(string $handle) {

        if(array_key_exists($handle, $this->globalSets)) {
            return $this->globalSets[$handle];
        }
        else if($globalSet = GlobalSet::findByHandle($handle)) {
            return $globalSet;
        }

        throw new \Exception('A global set with this handle does not exist: ' . $handle);

    }

    protected function initGlobalSets($viewData) {

        return array_filter($viewData, function($v, $k) {
            return is_object($v) && get_class($v) == 'Statamic\Globals\Variables';
        }, ARRAY_FILTER_USE_BOTH);

    }
}
