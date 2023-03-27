<p align="center">
    <a href="https://github.com/sulu/sulu/blob/master/LICENSE" target="_blank">
        <img src="https://img.shields.io/github/license/alengodev/alengoVoucherBundle?style=flat-square" alt="GitHub license">
    </a>
    <a href="https://github.com/sulu/sulu/releases" target="_blank">
        <img src="https://img.shields.io/github/v/tag/alengodev/alengoVoucherBundle?style=flat-square" alt="GitHub tag (latest SemVer)">
    </a> 
    <a href="https://github.com/sulu/sulu/releases" target="_blank">
        <img src="https://img.shields.io/badge/sulu%20compatibility-%3E=2.3-52b6ca.svg" alt="Sulu compatibility">
    </a>    
</p>

## Requirements

* PHP 8.1
* Symfony >=6.0

### Install the bundle

Execute the following [composer](https://getcomposer.org/) command

```bash
composer require alengo/alengo-voucher-bundle
```


### Enable the bundle

Enable the bundle by adding it to the list of registered bundles in the `config/bundles.php` file of your project:

 ```php
 return [
     /* ... */
     Alengo\Bundle\AlengoVoucherBundle\AlengoVoucherBundle::class => ['all' => true],
 ];
 ```

```bash
bin/console do:sch:up --force
```


### Configure the Bundle

Set the following config in your routes_admin.yaml

 ```yaml
app.voucher_categories_api:
    type: rest
    resource: Alengo\Bundle\AlengoVoucherBundle\Controller\Admin\VoucherCategoriesController
    prefix: /admin/api
    name_prefix: app.

app.voucher_orders_api:
    type: rest
    resource: Alengo\Bundle\AlengoVoucherBundle\Controller\Admin\VoucherOrdersController
    prefix: /admin/api
    name_prefix: app.
 ```

Define you default Sender Email in ENV Config.
 ```.dotenv
    VOUCHER_PER_WEBSPACE=true
 ```