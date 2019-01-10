<?php

/**
 * Session Wrapper
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\SessionWrapper;

/*
 * Session Interface
 */
interface SessionInterface
{
    /**
     * Discard session array changes and finish session
     * 
     * Finishes session without saving data. Thus the original values in session 
     * data are kept.
     * 
     * @return bool
     */
    public function abort() : bool;
    
    /**
     * Write session data and end session
     * 
     * @return bool
     */
    public function commit() : bool;
    
    /**
     * Create new session id
     * 
     * Used to create new session id for the current session. It returns 
     * collision free session id. 
     * 
     * If session is not active, collision check is omitted.
     * 
     * Session ID is created according to php.ini settings.
     * 
     * It is important to use the same user ID of your web server for GC task 
     * script. Otherwise, you may have permission problems especially with files 
     * save handler.
     * 
     * @param string|null $prefix
     * @return string
     */
    public function createId(?string $prefix = null) : string;
    
    /**
     * Destroys all data registered to a session
     * 
     * destroy() destroys all of the data associated with the current 
     * session. 
     * 
     * It does not unset any of the global variables associated with the session, 
     * or unset the session cookie. To use the session variables again, 
     * start() has to be called.
     * 
     * @return bool
     */
    public function destroy() : bool;
    
    /**
     * Perform session data garbage collection
     * 
     * session_gc() is used to perform session data GC(garbage collection). 
     * 
     * PHP does probability based session GC by default. 
     * 
     * Probability based GC works somewhat but it has few problems. 
     * 1) Low traffic sites' session data may not be deleted within the preferred 
     *    duration. 
     * 2) High traffic sites' GC may be too frequent GC. 
     * 3) GC is performed on the user's request and the user will experience a 
     *    GC delay.
     * 
     * Therefore, it is recommended to execute GC periodically for production 
     * systems using, e.g., "cron" for UNIX-like systems. Make sure to disable 
     * probability based GC by setting session.gc_probability to 0.
     * @return bool
     */
    public function gc() : bool;
    
    /**
     * Get and/or set the current session id
     * 
     * Used to get or set the session id for the current session. 
     * 
     * The constant SID can also be used to retrieve the current name and 
     * session id as a string suitable for adding to URLs.
     * 
     * @param string|null $session_id
     * @return string
     */
    public function id(?string $session_id) : string;
    
    /**
     * Get and/or set the current session name
     * 
     * Returns the name of the current session. If name is given, session_name() 
     * will update the session name and return the old session name.
     * 
     * If a new session name is supplied, name() modifies the HTTP cookie 
     * (and output content when session.transid is enabled). Once the HTTP cookie 
     * is sent, name() raises error. session_name() must be called before 
     * start() for the session to work properly.
     * 
     * The session name is reset to the default value stored in session.name at 
     * request startup time. Thus, you need to call name() for every request 
     * (and before start() is called).
     * 
     * @param string|null $name
     * @return string
     */
    public function name(?string $name = null) : string;
    
    /**
     * Update the current session id with a newly generated one
     * 
     * Will replace the current session id with a new one, and keep the current 
     * session information.
     * 
     * When session.use_trans_sid is enabled, output must be started after regenerateId() 
     * call. Otherwise, old session ID is used.
     * 
     * @param bool $delete_old_session
     * @return bool
     */
    public function regenerateId(bool $delete_old_session = false) : bool;
    
    /**
     * Re-initialize session array with original values
     * 
     * Reinitializes a session with original values stored in session storage. 
     * 
     * This function requires an active session and discards changes in $_SESSION.
     * 
     * @return bool
     */
    public function reset() : bool;
            
    /**
     * Set the session cookie parameters
     * 
     * Set cookie parameters defined in the php.ini file. The effect of this 
     * function only lasts for the duration of the script. Thus, you need to 
     * call setCookieParams() for every request and before start() is called.
     * 
     * This function updates the runtime ini values of the corresponding PHP ini 
     * configuration keys which can be retrieved with the ini_get().
     * 
     * @param int $lifetime - Lifetime of the session cookie, defined in seconds.
     * @param string|null $path Path on the domain where the cookie will work. 
     *                          Use a single slash ('/') for all paths on the domain.
     * @param string|null $domain - Cookie domain, for example 'www.php.net'. 
     *                              To make cookies visible on all subdomains then 
     *                              the domain must be prefixed with a dot like 
     *                              '.php.net'.
     * @param bool $secure - If TRUE cookie will only be sent over secure 
     *                       connections.
     * @param bool $httponly - If set to TRUE then PHP will attempt to send the 
     *                         httponly flag when setting the session cookie.
     */
    public function setCookieParams(int $lifetime, ?string $path = null, ?string $domain = null, bool $secure = false, bool $httponly = false);
    
    /**
     * Start new or resume existing session
     * 
     * Creates a session or resumes the current one based on a session identifier 
     * passed via a GET or POST request, or passed via a cookie.
     * 
     * When start() is called or when a session auto starts, PHP will call the 
     * open and read session save handlers. These will either be a built-in save 
     * handler provided by default or by PHP extensions (such as SQLite or 
     * Memcached); or can be custom handler as defined by session_set_save_handler(). 
     * 
     * The read callback will retrieve any existing session data (stored in a 
     * special serialized format) and will be unserialized and used to 
     * automatically populate the $_SESSION superglobal when the read callback 
     * returns the saved session data back to PHP session handling.
     * 
     * To use a named session, call name() before calling start().
     * 
     * When session.use_trans_sid is enabled, the start() function will register 
     * an internal output handler for URL rewriting.
     * 
     * If a user uses ob_gzhandler or similar with ob_start(), the function 
     * order is important for proper output. For example, ob_gzhandler must be 
     * registered before starting the session.
     * 
     * @param array $options If provided, this is an associative array of options 
     * that will override the currently set session configuration directives. 
     * 
     * The keys should not include the session. prefix.
     * 
     * In addition to the normal set of configuration directives, a read_and_close 
     * option may also be provided. If set to TRUE, this will result in the 
     * session being closed immediately after being read, thereby avoiding 
     * unnecessary locking if the session data won't be changed.
     * 
     * @return bool
     */
    public function start(array $options = []) : bool;
    
    /**
     * Returns the current session status
     * 
     * PHP_SESSION_DISABLED if sessions are disabled.
     * PHP_SESSION_NONE if sessions are enabled, but none exists.
     * PHP_SESSION_ACTIVE if sessions are enabled, and one exists.
     * 
     * @return int
     */
    public function status() : int;
    
    /**
     * Free all session variables
     * 
     * @return bool
     */
    public function unset() : bool;
    
    /**
     * Returns a session variable
     * 
     * Returns NULL when a variable doesn't exists
     * 
     * @param mixed $key
     */
    public function get($key);
    
    /**
     * Sets a session variable
     * 
     * @param type $value
     * @param type $value
     */
    public function set($key, $value);
    
    /**
     * Check if session variable exists
     * 
     * @param type $key
     * @return bool
     */
    public function has($key) : bool;
}
