<?php

/**
 * Footer
 * Main footer file for the theme.
 * php version 8.3.6
 *
 * @category   View
 * @package    Framework_Slim
 * @subpackage Mytheme
 * @author     Timur Safarov <tisafarov@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version    GIT: <ae6f1f9>
 * @link       https://github.com/timur-safarov/slim-restfullapi
 * @since      1.0.0
 */

?>

<h1>Login</h1>

<?php if (isset($error)) : ?>

    <p><?php echo $error; ?></p>

<?php endif; ?>

<form method="post" action="/login">
    <label for="email">email</label>
    <input type="email" name="email" id="email"
           value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">

    <label for="password">Password</label>
    <input type="password" name="password" id="password">

    <button>Log in</button>
</form>