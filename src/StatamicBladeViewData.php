<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Cache;

class StatamicBladeViewData {

    public $page;
    public $site;
    public $globals = [];
    public $navs = [];

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function init(array $viewData) {
        
        $this->page = $viewData['page'];
        $this->site = $viewData['site'];
        $this->globalSets = $this->initGlobalSets($viewData);
        $this->navs = $this->initNavs();
        
    }

    public function page() {
        return $this->page;
    }

    public function site() {
        return $this->site;
    }

    public function nav(string $handle) {

        if(!array_key_exists($handle, $this->navs)){
            throw new \Exception('A nav with this handle does not exist: ' . $handle);
        }

        return $this->navs[$handle];
    }

    public function globalSet(string $handle) {

        if(!array_key_exists($handle, $this->globalSets)){
            throw new \Exception('A global set with this handle does not exist: ' . $handle);
        }

        return $this->globalSets[$handle];
    }

    protected function initGlobalSets($viewData) {

        return array_filter($viewData, function($v, $k) {
            return is_object($v) && get_class($v) == 'Statamic\Globals\Variables';
        }, ARRAY_FILTER_USE_BOTH);

    }

    protected function initNavs() {

        $navs = [];

        foreach(config('statamic-blade-view-data.navs') as $handle => $options) {

            if($options['cache']) {

                $nav = Cache::rememberForever('statamic_nav_' . $handle, function() use ($handle, $options) {
                    return $this->getNav($handle, $options);
                });

            }
            else {
                $nav = $this->getNav($handle, $options);
            }

            $navs[$handle] = $nav;
        }

        return $navs;

    }

    protected function getNav($handle, $options) {

        if(!empty($options['params']['select'])) {
            return \Statamic::tag('nav:' . $handle)->params(['select' => $options['params']['select']])->fetch();
        }
        else {
            return \Statamic::tag('nav:' . $handle)->fetch();
        }

    }
}
