<?php
namespace app\components\helpers;
use yii;

/**
 * @author Lam Huynh <lamhq.com>
 */
class StringHelper {

	/**
	 * Returns a sanitized string to display in url
	 *
	 * Parameters:
	 *     $string - The string to sanitize.
	 *     $lowerCase - Force the string to lowercase?
	 *     $anal - If set to *true*, will remove all non-alphanumeric characters.
	 */
	static public function sanitize($string, $lowerCase = true, $anal = false) {
		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
					   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
					   "â€”", "â€“", ",", "<", ">", "/", "?");
		$clean = trim(str_replace($strip, "", strip_tags($string)));
		$clean = preg_replace('/\s+/', "-", $clean);
		$clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
		return ($lowerCase) ?
			(function_exists('mb_strtolower')) ?
				mb_strtolower($clean, 'UTF-8') :
				strtolower($clean) :
			$clean;
	}

}
