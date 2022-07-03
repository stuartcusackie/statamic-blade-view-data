# Statamic Blade View Data

Optimised facades to access Statamic data from within Blade views and components. Likely to become redundant when Statamic has better blade component support.


## Installation

```
composer require stuartcusackie/statamic-blade-view-data
```

## Publish

```
php please vendor:publish --tag=statamic-blade-view-data-config
```


## Config setup

Make sure to publish the config above and set up your required views.


## Usage

A Laravel facade is provided by the package. It has a few methods:  
- StatData::page() - Returns the Statamic page object
- StatData::site() - Returns Statamic site object
- StatData::globalSet('social') - Returns a specific global set

To use it in blade views:

```
StatData::globalSet('social')['facebook_url']
```

To use it in classes or components we need a forward slash:

```
$page = \StatData::page();
```
