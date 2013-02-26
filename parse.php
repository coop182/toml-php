<?php

include('./src/Toml/Parse.php');

$result = new Toml\Parser('./tests/example.toml');

print_r($result->get());