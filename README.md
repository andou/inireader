# PHP INI Files reader

A simple PHP class you can use to read ini files

## Installation

Add a dependency on `andou/inireader` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project.
You have to also add the relative repository.

Here is a minimal example of a `composer.json` file that just defines a dependency on `andou/inireader`:

```json
{
    "require": {
        "andou/inireader": "*"
    },
    "repositories": [
    {
      "type": "git",
      "url": "https://github.com/andou/inireader.git"
    }
  ],
}
```    

## Usage Examples
You can use `andou/inireader` in your project simply specifying the path where the ini file is located

```php
require_once './vendor/autoload.php';
/*
 * Whether to read sections or not
 */
$read_sections = TRUE;
$config = Andou\Inireader::getInstance(CONFIG_FILE, $read_sections);
/*
 * This reads
 * [logs]
 * write_folder = 'path/to/your/folder'
 */
echo $config->getLogsWriteFolder();
/*
 * Alternatively
 */
echo $config->getConfiguration('logs_write_folder');

```

