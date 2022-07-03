# Statamic Blade View Data

Facades to access Statamic data from within Blade views and components. Also includes built-in caching of navigations.


## Installation

```
composer require stuartcusackie/statamic-blade-view-data
```

## Publish

```
php please vendor:publish --tag=statamic-blade-view-data-config
```


## Config setup

Make sure to publish the config above and set up your required view and navigation options.


## Usage

A Laravel facade is provided by the package. It has a few methods:  
- StatData::page() - Returns the Statamic page object
- StatData::site() - Returns Statamic site object
- StatData::nav('handle') - Returns a specific nav
- StatData::globalSet('social') - Returns a specific global set

To use it in blade views:

```
@foreach(StatData::nav('main') as $item)
  {{-- Do whatever --}}
@endforeach
```

To use it in classes we need a forward slash:

```
$page = \StatData::page();
```


## Cache Clearing

If you enabled caching on navs or other view data then it will be cached forever, so you will have to flush it at some point.

A good way to flush cached items is to use your EventServiceProvider class. Here is an example for flushing a cached navigation
after it has been edited in the control panel:

```
public function boot()
{
  Event::listen(function (NavTreeSaved $event) {

    // Just append your navs handle to 'statamic_nav_your-handle'
    Cache::forget('statamic_nav_main');
  });
```