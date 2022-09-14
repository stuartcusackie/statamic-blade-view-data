# Statamic Blade View Data

Facades to access Statamic data from within Blade views and components. Also includes built-in caching of navigations.


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
```
