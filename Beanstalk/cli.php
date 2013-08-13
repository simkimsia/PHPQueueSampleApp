#!/usr/bin/php
<?php
// Usage:
// php cli.php add --data '{"boo":"bar","foo":"car"}'
// php cli.php work
require_once __DIR__ . '/config.php';

$queue_name = 'ConvertEPub';
$action = $argv[1];
$options = array('queue'=>$queue_name);
$c = new PHPQueue\Cli($options);

switch ($action) {
    case 'add':
        $payload_json = $argv[3];
        $payload = json_decode($payload_json, true);
        $c->add($payload);
        break;
    case 'work':
        $c->work();
        break;
    case 'get':
        break;
    default:
        echo "Error: No action declared...\n";
        break;
}
