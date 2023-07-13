<?php


use Loader\DotEnvLoader;

require __DIR__ . '/../vendor/autoload.php';

$envLoader = new DotEnvLoader();
$envLoader->load(__DIR__  . "/../.env");
echo '<pre>';

print_r($_SERVER);
echo '</pre>';
?>
<h1>test</h1>