<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('enum_select'))
{
	function enum_select( $rows )
	{
		$regex = "/'(.*?)'/";
		preg_match_all( $regex , $rows, $enum_array );
		$enum_fields = $enum_array[1];
		foreach ($enum_fields as $key=>$value)
		{
			$enums[$value] = humanize($value);
		}
		return $enums;
	}
}