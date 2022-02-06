<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection AutoloadingIssuesInspection */
/** @noinspection PhpUnused */

/**
 * Modello delle Categorie.
 * @author         Pereira Pulido Nuno Ricardo | Namaless | namaless@gmail.com
 * @copyright      Copyright 1981-2008, Namaless.
 * @link           http://www.namaless.com Namaless Blog
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class Favourite extends AppModel
{
    /**
     * Nome del modello.
     * @var string
     */
    public $name = 'Favourite';

    /**
     * Relazione uno a molti.
     * @var mixed
     */
    public $belongsTo = 'Videos';

    /**
     * Validazione dei campi.
     * @var array
     */

    /*
        var $validate = array(
            'title' => array(
                'rule'		=> array('minLength', 1),
                'required'	=> TRUE
            )
        );
    */

}


