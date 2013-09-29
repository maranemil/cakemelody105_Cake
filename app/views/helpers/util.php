<?php
/**
 * Common utilities for helpers
 * @author RosSoft
 * @version 0.20
 * @package helpers
 */
class UtilHelper extends Helper
{
	var $helpers=array('Html');

	/**
	 * Converts a string like Model/field to
	 * the string data[Model][field] useful for forms (input name)
	 * @param string $fieldName Model/field
	 * @return string
	 */
	function fieldname_to_formname($fieldName)
	{
		if (strpos($fieldName,'/'))
		{
			$arr=split('/',$fieldName);
			$model=$arr[0];
			$field=$arr[1];
			return "data[{$arr[0]}][{$arr[1]}]";
		}
		else
		{
			return $fieldName;
		}
	}

	/**
	 * Retrieves the value from data array of field passed
	 * @param string $fieldName Model/field
	 * @return string The value from the data array
	 */
	function retrieve_value($fieldName)
	{
		if (strpos($fieldName,'/'))
		{
			$arr=split('/',$fieldName);
			$model=$arr[0];
			$field=$arr[1];
			if (isset($this->params['data'][$model][$field]))
			{
                 return $this->params['data'][$model][$field];
            }
            elseif (isset($this->data[$model][$field]))
            {
            	return $this->data[$model][$field];
            }
            else return false;
		}
	}

	/**
	 * This is a copy of the same function in HtmlHelper
	 * Returns a space-delimited string with items of the $options array. If a
	 * key of $options array happens to be one of:
	 *    + 'compact'
	 *    + 'checked'
	 *    + 'declare'
	 *    + 'readonly'
	 *    + 'disabled'
	 *    + 'selected'
	 *    + 'defer'
	 *    + 'ismap'
	 *    + 'nohref'
	 *    + 'noshade'
	 *    + 'nowrap'
	 *    + 'multiple'
	 *    + 'noresize'
	 *
	 * And its value is one of:
	 *    + 1
	 *    + true
	 *    + 'true'
	 *
	 * Then the value will be reset to be identical with key's name.
	 * If the value is not one of these 3, the parameter is not output.
	 *
	 * @param  array  $options      Array of options.
	 * @param  array  $exclude      Array of options to be excluded.
	 * @param  string $insertBefore String to be inserted before options.
	 * @param  string $insertAfter  String to be inserted ater options.
	 * @return string
	 */
	function parse_attributes($options, $exclude = null, $insertBefore = ' ',
    $insertAfter = null)
    {
        $minimizedAttributes = array(
        'compact',
        'checked',
        'declare',
        'readonly',
        'disabled',
        'selected',
        'defer',
        'ismap',
        'nohref',
        'noshade',
        'nowrap',
        'multiple',
        'noresize');

        if (!is_array($exclude))
        {
            $exclude = array();
        }

        if (is_array($options))
        {
            $out = array();

            foreach ($options as $key => $value)
            {
                if (!in_array($key, $exclude))
                {
                    if (in_array($key, $minimizedAttributes) && ($value === 1 ||
                    $value === true || $value === 'true' || in_array($value,
                    $minimizedAttributes)))
                    {
                        $value = $key;
                    }
                    elseif (in_array($key, $minimizedAttributes))
                    {
                        continue;
                    }
                    $out[] = "{$key}=\"{$value}\"";
                }
            }
            $out = join(' ', $out);
            return $out? $insertBefore.$out.$insertAfter: null;
        }
        else
        {
            return $options? $insertBefore.$options.$insertAfter: null;
        }
    }

	/**
	 * Converts a string like Model/field to Model/Field
	 * useful for forms (DOM ID)
	 * @param string $fieldName Model/field
	 * @return string ModelField
	 */
	function fieldname_to_formid($fieldName)
	{
		if (strpos($fieldName,'/'))
		{
			$arr=split('/',$fieldName);
			return $arr[0] . Inflector::camelize($arr[1]);
		}
		else
		{
			return $fieldName;
		}
	}

	/**
	 * Converts a cake url to an absolute url
	 *
	 * @param string $cake_url An URL like /controller/action/param1/param2
	 * @return string An absolute url like http://yourdomain.com/your_path_to_cake/controller/action/param1/param2
	 */
	function to_absolute_url($cake_url)
	{
		return FULL_BASE_URL . $this->Html->url($cake_url);
	}
}

?>