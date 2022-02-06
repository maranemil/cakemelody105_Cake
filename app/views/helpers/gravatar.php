<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnused */
/** @noinspection AutoloadingIssuesInspection */

/**
 * Helper Gravatar (www.gravatar.com)
 * @author         Pereira Pulido Nuno Ricardo | Namaless | namaless@gmail.com
 * @copyright      Copyright 1981-2008, Namaless.
 * @link           http://www.namaless.com Namaless Blog
 * @version        1.0
 * @license        http://www.opensource.org/licenses/mit-license.php The MIT License
 */

class GravatarHelper extends AppHelper
{
    /**
     * Restituisce l'url dell'immagine.
     *
     * @param mixed $input Input da inviare a Gravatar.com
     *
     * @return string URL dell'immagine
     */
    public function imgURL($input)
    {
        return $this->makeURL($input);
    }

    /**
     * Restituisce il tag HTML dell'immagine.
     *
     * @param mixed $input Input da inviare a Gravatar.com
     * @param string $class Nome della classe da aggiungere al tag HTML.
     *
     * @return string Tag HTML dell'immagine.
     */
    public function imgTag($input, $class = false)
    {
        $url = $this->makeURL($input);
        $classHTML = $class !== false ? 'class="' . $class . '"' : '';
        return '<img src="' . $url . '" ' . $classHTML . ' />';
    }

    /**
     * Crea l'URL da inviare.
     *
     * @param mixed $input Input da inviare a Gravatar.com
     *
     * @return string URL per richiamare l'immagine.
     */
    private function makeURL($input)
    {
        $baseURL = "https://www.gravatar.com/avatar/";

        if (is_string($input)) {
            return $baseURL . md5($input);
        }

        if (is_array($input)) {
            $URL = $baseURL . md5($input['email']) . "/?";

            if (array_key_exists('rating', $input)) {
                $URL .= "r=" . $input['rating'] . "&";
            }

            if (array_key_exists('size', $input)) {
                $URL .= "s=" . $input['size'] . "&";
            }

            if (array_key_exists('default', $input)) {
                $URL .= "default=" . urlencode($input['default']);
            }

            return $URL;
        }

        return  '';
    }
}
