<?php

namespace stuartcusackie\StatamicBladeViewData;

use Illuminate\Support\Facades\Cache;

class StatamicViewData {

    public $page;
    public $site;
    public $globals = [];
    public $navs = [];

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $viewData)
    {
        $this->page = $viewData['page'];
        $this->site = $viewData['site'];
        $this->globals = $this->initGlobals($viewData);
        $this->navs = $this->initNavs();
    }

    protected function initGlobals($viewData) {

        return array_filter($viewData, function($v, $k) {
            return is_string($v) && strpos($v, 'Statamic\Globals\GlobalSet') !== false;
        }, ARRAY_FILTER_USE_BOTH);

    }

    protected function initNavs() {

        // Store these in a config file
        $navs = [];

        foreach(config('statamic-blade.view-data.navs') as $handle => $options) {

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