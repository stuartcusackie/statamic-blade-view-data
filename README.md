# Statamic Blade View Data

Facades to access Statamic data from within Blade components. This package extracts the Statamic data that is passed to your selected template and adds it to a singleton that can be accessed anywhere in your application.

## DO NOT USE

Since writing this package the devs at Statamic have found a better way to access Statamic data. Use the Cascade facade in any controller or blade component:
```
use Facades\Statamic\View\Cascade;

$cascade = Cascade::instance()->toArray();
$page = $cascade['page'];
$globalAnalytics = $cascade['analytics'];
```


## Installation

```
composer require stuartcusackie/statamic-blade-view-data
```

## Usage

A Laravel facade is provided by the package. It has a few methods:
- StatData::context() - Returns all of the view data
- StatData::page() - Returns the Statamic page object
- StatData::site() - Returns Statamic site object
- StatData::globalSet('social') - Returns a specific global set

To use it in blade views:

```
StatData::globalSet('social')['facebook_url']
```

To use it in classes we need a forward slash:

```
$page = \StatData::page();
```


## Custom Routes

Custom routes that are set up in web.php and that use custom controllers won't be initialised with the Statamic data.

If you are returning a view in your custom controllers then you can initialise the blade data like so:

```
View::composer('pages/custom-view', function ($view) {
  \StatData::init($viewData);
});

return (new \Statamic\View\View)
  ->template('pages/custom-view')
  ->layout('layouts/app')
  ->with(['page' => $page]);
```
