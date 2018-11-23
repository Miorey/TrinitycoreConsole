<?php

require __DIR__.'/../../vendor/autoload.php';
use FreedomCore\TrinityCore\Console\Client;
$console = new Client('gabknight', 'GAB13naki');
$console->send()->message('test', 'Message in the middle of the screen by administrator');