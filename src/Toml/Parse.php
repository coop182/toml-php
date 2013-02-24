<?php

namespace Toml;

class Parser {

	private $input;
	public $result;

	public function __construct($file) {

		// Read the toml file in.
		$this->input = file($file);

		// Crappy hack to deal with nested arrays, the following gets them on to one line so
		// they are easier to deal with. Should be json_decode-able...
		$this->input = implode('#nl#', preg_replace(array('/\n/'), array('#nl#'), $this->input));
		preg_match_all('/[^#]*=[^#]*\[([^=]*)\]/', $this->input, $matches);
		foreach($matches[0] as $match) {
			$this->input = str_replace($match, str_replace('#nl#', '', $match), $this->input);
		}
		$this->input = explode('#nl#', str_replace('#nl##nl#', '#nl#', $this->input));

		// Init the results class
		$this->result = new \StdClass();

		// Parse it!
		$this->parse();

	}

	private function parse() {

		// Setup the result object to be passed around by reference
		$this->previous_key_group = $this->current_key_group = &$this->result;

		// Loop through the lines
		foreach ($this->input as $linenum => $line) {

			if(preg_match('/^$/i', $line)) {
				// empty line

				$this->current_key_group = &$this->previous_key_group;

			} else if (preg_match('/^\s*\[(.*)\]/i', $line, $matches)) {
				// key group

				$key = trim($matches[1]);

				$this->previous_key_group = &$this->current_key_group;

				// Deal with nested key groups
				$groups = explode('.', $key);
				foreach($groups as $key) {

					if(!isset($this->current_key_group->$key)) {
						$this->current_key_group->$key = new \StdClass();
					}

					$this->current_key_group = &$this->current_key_group->$key;

				}

			} else if(preg_match('/\s?([^#]*)=([^#]*)/i', $line, $matches)) {
				// key value pair

				$key = trim($matches[1]);
				$val = trim($matches[2]);

				$this->current_key_group->$key = $this->val_type($val);

			}

		}

	}

	private function val_type($val) {

		// Check if true
		if($val === "true") {
			return true;
		}

		// Check if false
		if($val === "false") {
			return false;
		}

		// Check if integer
		if((string) intval($val) === $val) {
			return intval($val);
		}

		// Check if float
		if((string) floatval($val) === $val) {
			return floatval($val);
		}

		// Check if array
		if(preg_match('/\[/', $val)) {
			// Probably not the best solution...
			return json_decode($val);
		}

		// Check if string
		if(preg_match('/\"(.*)\"/', $val, $matches)) {
			return stripcslashes($matches[1]);
		}

		// Check if datetime
		if ($datetime = strtotime($val)) {
			return $datetime;
		}

		return $val;

	}

}
