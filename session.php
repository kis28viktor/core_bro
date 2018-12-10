<?php

/**
 * Class Session
 */
class Session
{
    /**
     * Constructor
     */
    public function __construct()
    {
        if ( $this->is_session_started() === FALSE ) {
            session_start();
        }
    }

    /**
     * Get value from session
     *
     * @param string|int $val
     * @return null|string|double|int|bool
     */
    public function get($val)
    {
        if (isset($_SESSION[$val])) {
            return $_SESSION[$val];
        } else {
            return null;
        }
    }

    /**
     * Set value to session massive
     *
     * @param string|int $key
     * @param null|string|double|int|bool $val
     */
    public function set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    /**
     * Delete some value from the session
     *
     * @param string|int $val
     */
    public function unsetValue($val)
    {
        unset($_SESSION[$val]);
    }

    /**
     * Add some error
     *
     * Add some string to the session massive with 'errors' key, which is used by 'displayErrors' function
     *
     * @param string|array $message
     */
    public function addError($message)
    {
        $this->set('errors', $message);
    }

    /**
     * Check if session has been started
     *
     * @return bool
     */
    function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
}
