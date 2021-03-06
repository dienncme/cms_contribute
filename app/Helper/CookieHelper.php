<?php

namespace App\Helper;

class CookieHelper
{
    const COOKIE_KEY = 'contributor_login_remember';

    /**
     * @param string $dataCookie
     * @param int|float $minutes
     * @return $this
     */
    public function create(string $dataCookie, int $minutes = 3600 * 30): CookieHelper
    {
        cookie(self::COOKIE_KEY, $dataCookie, $minutes);

        return $this;
    }

    /**
     * @param string $dataCookie
     * @param int|float $minutes
     * @return $this
     */
    public function delete($dataCookie = '', int $minutes = -3600 * 30): CookieHelper
    {
        cookie(self::COOKIE_KEY, $dataCookie, $minutes);

        return $this;
    }

    /**
     * @return string|null
     */
    public function get()
    {
       return cookie(self::COOKIE_KEY)->getValue();
    }

}
