TOML Parser in PHP
====

An parser for [TOML](https://github.com/mojombo/toml) written in PHP.

## Usage

<code>

<?php

include('./src/Toml/Parse.php');

use Toml\Parser;

$parser = new Parser('./tests/example.toml');

print_r($parser->result);

</code>