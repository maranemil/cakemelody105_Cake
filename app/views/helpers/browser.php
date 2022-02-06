<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */

/**
 * Helper per identificare il browser utilizzato dall'utente.
 * @author  Emanuele "Zuck" Bertoldi
 * @email zuck@fastwebnet.it
 * @version 1.0
 */

class BrowserHelper extends AppHelper
{
    /**
     * name => string_id
     * @var $array
     */
    public $browsers
        = array(
            'ie6'     => 'MSIE 6.0',
            'ie7'     => 'MSIE 7.0',
            'Firefox' => 'Firefox',
            'Opera'   => 'Opera',
            'Safari'  => 'Safari'
        );

    /**
     * Restituisce il nome del browser utilizzato.
     * @return string
     */
    public function identify()
    {
        foreach ($this->browsers as $name) {
            if ($this->check($name)) {
                return $name;
            }
        }
        return false;
    }

    /**
     * Verifica se il browser indicato � quello utilizzato dall'utente.
     *
     * @param string $name Nome del browser.
     *
     * @return boolean
     */
    public function check($name)
    {
        return preg_match($this->browsers[$name], env('HTTP_USER_AGENT'));
    }
}

