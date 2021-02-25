<?php

/**
 * Modello dell'Applicazione.
 * @author         Pereira Pulido Nuno Ricardo | Namaless | namaless@gmail.com
 * @copyright      Copyright 1981-2008, Namaless.
 * @link           http://www.namaless.com Namaless Blog
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class AppModel extends Model {
   /**
	* Caricamento dei Behavior.
	* @var mixed
	*/
   var $actsAs = array('Sluggable' => array('overwrite' => true));

   /**
	* Metodo che gestisce il ritorno dell'array della validazione.
	*
	* @param string $field Nome del campo.
	* @param string $value Testo dell'errore.
	*
	* @return array
	*/
   function invalidate($field, $value = true) {
	  return parent::invalidate($field, __($value, true));
   }
}