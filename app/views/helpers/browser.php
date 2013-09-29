<?php

/**
 * Helper per identificare il browser utilizzato dall'utente.
 *
 * @author Emanuele "Zuck" Bertoldi
 * @email zuck@fastwebnet.it
 * @version 1.0
 */

class BrowserHelper extends AppHelper
{
    /**
     * name => string_id
     *
     * @var $array
     */
    var $browsers = array(
		'ie6'		=> 'MSIE 6.0',
		'ie7'		=> 'MSIE 7.0',
		'Firefox'	=> 'Firefox',
		'Opera'		=> 'Opera',
		'Safari'	=> 'Safari'
	);

    /**
     * Restituisce il nome del browser utilizzato.
     *
     * @return string
     */
    function identify()
	{
        foreach ( $this->browsers AS $key => $name )
		{
            if ( $this->check($name) )
			{
                return $name;
            }
        }
    }

    /**
     * Verifica se il browser indicato è quello utilizzato dall'utente.
     *
     * @param string $name Nome del browser.
     * @return boolean
     */
    function check($name)
	{
        return @ereg($this->browsers[$name], env('HTTP_USER_AGENT'));
    }
}

?>