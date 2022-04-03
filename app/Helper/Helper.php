<?php

namespace App\Helper;

class Helper
{

	public static function numOnly($input) {
		return preg_replace('/[^0-9]/','', $input);
	}

	public static function generate_id_number($input, $pad_len = 5, $prefix = null) {
		if(strlen($input) > $pad_len)
			$pad_len = strlen($input);

		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

    public static function clean($string) {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9_\-]/', '', strtolower($string)); // Removes special chars.
    }
}
