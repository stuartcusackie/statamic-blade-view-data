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
        $this->globals = $this->initGlobals($viewData);
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

    protected function initGlobals($viewData) {

        return array_filter($viewData, function($v, $k) {
            return is_string($v) && strpos($v, 'Statamic\Globals\GlobalSet') !== false;
        }, ARRAY_FILTER_USE_BOTH);

    }

    protected function initNavs() {

        $navs = [];

        foreach(config('statamic-blade-view-data.navs') as $handle => $options) {

            if(!empty($options['params']['select'])) {
                $nav = \Statamic::tag('nav:' . $handle)->params(['select' => $options['params']['select']])->fetch();
            }
            else {
                $nav = \Statamic::tag('nav:' . $handle)->fetch();
            }

            if($options['cache']) {

                Cache::rememberForever('statamic_nav_' . $handle, function() use($nav) {
                    return $nav;
                });

            }

            $navs[$handle] = $nav;
        }

        return $navs;

    }
}