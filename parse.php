<?php

include('./src/Toml/Parse.php');

use Toml\Parser;

$parser = new Parser('./tests/example.toml');

print_r($parser->result);