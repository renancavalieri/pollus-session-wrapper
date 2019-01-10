<?php

/**
 * Session Wrapper
 * @license https://opensource.org/licenses/MIT MIT
 * @author Renan Cavalieri <renan@tecdicas.com>
 */

namespace Pollus\SessionWrapper;

use Pollus\SessionWrapper\SessionInterface;

class Session implements SessionInterface
{
    /**
     * {@inheritDoc}
     */
    public function abort(): bool
    {
        return session_abort(); 
    }

    /**
     * {@inheritDoc}
     */
    public function commit(): bool
    {
        return session_write_close();
    }

    /**
     * {@inheritDoc}
     */
    public function createId(?string $prefix = null): string
    {
        if ($prefix !== null)
        {
            return session_create_id($prefix);
        }
        return session_create_id();
    }

    /**
     * {@inheritDoc}
     */
    public function destroy(): bool
    {
        return session_destroy();
    }

    /**
     * {@inheritDoc}
     */
    public function gc(): bool
    {
       return session_gc(); 
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if ($this->has($key))
        {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key): bool
    {
        if (isset($_SESSION[$key]))
        {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function id(?string $session_id = null): string
    {
        if ($session_id !== null)
        {
            return session_id($session_id);
        }
        return session_id();
    }

    /**
     * {@inheritDoc}
     */
    public function name(string $name = null): string
    {
        if ($name !== null)
        {
            return session_name($name);
        }
        return session_name();
    }

    /**
     * {@inheritDoc}
     */
    public function regenerateId(bool $delete_old_session = false): bool
    {
       return session_regenerate_id($delete_old_session);
    }

    /**
     * {@inheritDoc}
     */
    public function reset(): bool
    {
        return session_reset();
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setCookieParams(int $lifetime, ?string $path = null, ?string $domain = null, bool $secure = false, bool $httponly = false)
    {
        return session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
    }

    /**
     * {@inheritDoc}
     */
    public function start(array $options = array()): bool
    {
       return session_start($options);
    }

    /**
     * {@inheritDoc}
     */
    public function status(): int
    {
        return session_status();
    }

    /**
     * {@inheritDoc}
     */
    public function unset(): bool
    {
        return session_unset();
    }
}
