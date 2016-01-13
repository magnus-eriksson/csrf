# A small CSRF package for PHP

Quickly generate and validate tokens to prevent Cross-Site Request Forgery (CSRF) attacks. 

> *__Important:__ This package only helps you with the CSRF tokens. To truly be safe from CSRF, you also need to protect yourself against [Cross-site scripting (XSS)](https://en.wikipedia.org/wiki/Cross-site_scripting) as well.* 


## Install

Git clone or use composer to download the package with the following command:
```
composer require maer/csrf 0.*
```

## Usage
Include composers autoloader or include the files in the `src/` folder manually. *(start with `CsrfInterface.php`-file)*

#### Create a new instance ####

```
$csrf = new Maer\Security\Csrf\Csrf();

```

*__Important:__ You can create a new instance when ever in your application, but before you make any calls to it, you need to start the session yourself. This package does not make any assumptions on how you manage your sessions (you might use: session_start() or you might use Symfonys Session package etc...)*


#### Approach 1: Manually add the hidden field ####
```
<form method="post" action="...">

    <input type="hidden" name="csrftoken" value="<?= $csrf->getToken() ?>" />

    ...

</form>
```

#### Approach 2: Generate the hidden field ####
```
<form method="post" action="...">

    <?= $csrf->getTokenField() ?>

    ...

</form>

```


#### Validate
When receiving the post:
```
if ($csrf->validateToken($_POST['csrftoken'])) {
    echo "Yay! It's a valid token!";
} else {
    echo "Nope. That token isn't valid!";
}
```

#### Named tokens

All methods takes an optional `$name` argument. This gives you the option of having multiple tokens through out your application. For example:

```
$csrf->getToken();
$csrf->getToken('login-form');
$csrf->getToken('something-else');
```
The above will generate three different tokens and the same goes for the `getTokenField()`-method.

To validate named tokens, set the name as the second argument to the `validateToken()`-method:

```
$csrf->validateToken($_POST['csrftoken'], 'login-form');
```

#### Regenerate tokens

If you want to invalidate an existing token, use the `regenerateToken()`-method. This method also returns the new token, so if you want to have different tokens every time a form is loaded, you can use this method instead of `generateToken()`
```
$token = $csrf->regenerateToken();

// or for a named token
$token = $csrf->regenerateToken('login-form');
```


#### Reset/remove all tokens

This will remove all tokens, named or not.
```
$csrf->resetAll();
```


## Note
If you have any questions, suggestions or issues, let me know!

Happy coding!