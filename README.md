## VietNam Maps

Database of administrative units of Vietnam

Data are taken directly from the General Statistics Office of Vietnam.
 
Make sure it is always the most current and accurate.

## Install

```shell
composer require hoangphi/vietnam-maps
```

#### Copy file config và migration

```shell
php artisan vendor:publish --provider="HoangPhi\VietnamMap\VietnamMapServiceProvider"
```

#### Customize config và migration

1. Rename table

Open file `config/vietnam-maps.php` and config:

```php
'tables' => [
    'provinces' => 'provinces',
    'districts' => 'districts',
    'wards'     => 'wards',
],
```

2. Rename column

Open file `config/vietnam-maps.php` and config:

```php
'columns' => [
    'name'        => 'name',
    'gso_id'      => 'gso_id',
    'province_id' => 'province_id',
    'district_id' => 'district_id',
],
```

3. Add column

Open the following migration files and customize if you need:

```shell
database/migrations/2020_01_01_000000_create_vietnam_maps_table.php
```

## Run migration

```shell
php artisan migrate
```

## Download và import into database

```shell
php artisan vietnam-map:download
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
