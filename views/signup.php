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

<h1>Signup</h1>

<?php if (isset($errors)) : ?>

    <ul>
        <?php foreach ($errors as $field) : ?>
            <?php foreach ($field as $error) : ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<form method="post" action="/signup">
    <label for="name">Name</label>
    <input type="text" name="name" id="name"
           value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>">

    <label for="email">email</label>
    <input type="email" name="email" id="email"
           value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>">

    <label for="password">Password</label>
    <input type="password" name="password" id="password" 
            size="8" minlength="8" maxlength="16">

    <label for="password_confirmation">Repeat password</label>
    <input type="password" name="password_confirmation"
           id="password_confirmation">

    <button>Sign up</button>
</form>