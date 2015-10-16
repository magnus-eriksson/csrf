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

## More...
Above is the basic usage but there are some more stuff available. I'll update this guide soon...