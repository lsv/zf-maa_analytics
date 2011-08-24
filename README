# Setup

## Requirements

Zend_Framework 1.11.3 (hope that newer versions also works with this code:)

*   Requires <span class="caps">PHP</span> 5.3.x
*   Small modifications to Bootstrap and application.ini

## Bootstrap

There are few things that needed for this to run

In our application/Bootstrap.php we need to add this method

`protected function _initConfig()
{
$config = new Zend_Config($this-&#62;getOptions(), true);
Zend_Registry::set(&#39;config&#39;, $config);
return $config;
}`

Thats because I dont see any other .shortcuts. to get the Zend_Config application.ini data

## application.ini

In our application/configs/application.ini we need the following

`autoloadernamespaces[] = Maa`

So the Directory can be found

## Examples

Now library/Maa/_examples/application.ini.sample for more configuration

For a an working example see application/layouts/scripts/layout.phtml

For documentation see public/doc/index.html
