# SEO Generation
Script for mass generating SEO links for products, categories and brands in stores based on the Opencart engine

## Using 
1. Upload the **engine.php** file to the root of the project directory

2. Substitute your values(strings 11-12)

```
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'xxx');
define('DB_PASSWORD', 'xxxx');
define('DB_DATABASE', 'xxxx');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');

define('LANGUAGE_ID', 1);
define('STORE_ID', 0);
```

   or require config.php
 
 ```
 require_once 'config.php'
 ```
 
 3. Run and enjoy
 
  ```
  <host>/engine.php
  ```
