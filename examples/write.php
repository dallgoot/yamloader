<?php
define('PROJECT_ROOT', __DIR__."/../");
require_once PROJECT_ROOT . 'vendor/autoload.php';

use Dallgoot\Yaml;
use Dallgoot\Yaml\{Loader, Dumper};

/* USE CASE 1
* load and parse if file exists
*/
// $yaml = (new Loader('./references/Example 2.28.yml', null, 0))->parse();
// $yaml = (new Loader('./dummy.yml', null, 0))->parse();
// var_dump($yaml);
$testName = 'yamlObject_properties';
$text = Yaml::dump((include PROJECT_ROOT . "tests/cases/dumping/$testName.php"), 0);

$nameResultPair = get_object_vars(Yaml::parseFile(PROJECT_ROOT . '/tests/definitions/dumping_tests.yml'));

// var_dump($nameResultPair);

if ($nameResultPair[$testName] !== $text) {
    var_dump('EXPECTED', $nameResultPair[$testName]);
    var_dump('RECEIVED', $text);
} else echo 'OK';