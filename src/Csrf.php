<?php namespace Maer\Security\Csrf;
/**
 * A small CSRF library for generating/verifying CSRF tokens
 * 
 * @author     Magnus Eriksson <mange@reloop.se>
 * @version    0.1.0
 * @package    Maer
 * @subpackage Csrf
 */
class Csrf implements CsrfInterface
{
    /**
     * The default token name if user omit the name from the requests
     * @var string
     */
    protected $defaultName = 'default';

    /**
     * Collection that holds all tokens from the session
     * @var array
     */
    protected $tokens      = [];

    /**
     * Key name for the session with the token collection
     * @var string
     */
    protected $key         = 'csrf_tokens';

    /**
     * Have we initialized the library yet?
     * @var boolean
     */
    protected $initialized = false;


    /**
     * {@inheritdoc}
     */
    public function getToken($name = null)
    {
        $this->initialize();

        $hName = $this->hashName($name);
        $token = isset($this->tokens[$hName]) ? $this->tokens[$hName]: null;
        
        return $token?: $this->regenerateToken($name);
    }


    /**
     * Get html markup for a hidden input CSRF field
     * 
     * @param  string   $name   If omitted, the default name will be used
     * @return string   Html markup
     */
    public function getTokenField($name = null)
    {
        $token = $this->getToken($name);
        return '<input type="hidden" name="csrftoken" value="' . $token . '" />';
    }


    /**
     * Validate a token
     * 
     * @param  string   $userToken  The token to validate
     * @param  string   $name       If omitted, the default name will be used
     * @return bool
     */
    public function validateToken($userToken, $name = null)
    {
        $token = $this->getToken($name);
        return !is_null($userToken) && $token === $userToken;
    }

    
    /**
     * Regenerate a CSRF token
     * 
     * @param  string   $name   If omitted, the default token will be regenerated
     */
    public function regenerateToken($name = null)
    {
        $this->initialize();

        $name                 = $this->hashName($name);
        $this->tokens[$name]  = base64_encode(openssl_random_pseudo_bytes(64));

        if (!isset($_SESSION[$this->key]) || !is_array($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }

        $_SESSION[$this->key][$name] = $this->tokens[$name];

        return $this->tokens[$name];
    }


    /**
     * Reset/delete all tokens
     */
    public function resetAll()
    {
        $this->initialize();

        $this->tokens         = [];
        unset($_SESSION[$this->key]);
    }


    /**
     * Get current session tokens, if there are any.
     * 
     * @return void
     */
    protected function initialize()
    {
        if ($this->initialized) {
            // Already initialized
            return true;
        }
        
        if (session_status() !== PHP_SESSION_NONE) {
            
            if (isset($_SESSION[$this->key]) && is_array($_SESSION[$this->key])) {
                // Get the token collection from the session, if we got any
                $this->tokens = $_SESSION[$this->key];
            }
            
            $this->initialized = true;

        } else {
            
            throw new CsrfSessionException('A session must be started before the Csrf library can be used');

        }
    }


    /**
     * Normalize and MD5 hash the name (this is not for security reasons
     * but rather to remove weird characters in the name)
     * 
     * @param  string   $name   If omitted, the default token will be regenerated
     * @return string
     */
    protected function hashName($name = null)
    {
        $name = strtolower($name);
        return md5($name?: $this->defaultName);
    }
}