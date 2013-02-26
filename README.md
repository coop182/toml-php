TOML Parser in PHP
====

An parser for [TOML](https://github.com/mojombo/toml) written in PHP.

## Usage
    
    <?php
    
    include('./src/Toml/Parse.php');
    
    $result = new Toml\Parser('./tests/example.toml');
    
    print_r($result->get());

## Todo

A lot...

1. Error handling.
2. More robust array code.
3. Double check conforming to spec.
4. Tests.
