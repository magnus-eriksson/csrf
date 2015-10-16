<?php namespace Maer\Security\Csrf;
/**
 * A small CSRF library for generating/verifying CSRF tokens
 * 
 * @author     Magnus Eriksson <mange@reloop.se>
 * @version    0.1.0
 * @package    Maer
 * @subpackage Csrf
 */
interface CsrfInterface
{

    /**
     * Get a CSRF token
     * 
     * @param  string   $name   If omitted, the default name will be used
     * @return string
     */
    public function getToken($name = null);


    /**
     * Get html markup for a hidden input CSRF field
     * 
     * @param  string   $name   If omitted, the default name will be used
     * @return string   Html markup
     */
    public function getTokenField($name = null);


    /**
     * Validate a token
     * 
     * @param  string   $userToken  The token to validate
     * @param  string   $name       If omitted, the default name will be used
     * @return bool
     */
    public function validateToken($userToken, $name = null);

    
    /**
     * Regenerate a CSRF token
     * 
     * @param  string   $name   If omitted, the default token will be regenerated
     */
    public function regenerateToken($name = null);


    /**
     * Reset/delete all tokens
     */
    public function resetAll();

}