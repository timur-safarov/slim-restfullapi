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

<h1>Welcome</h1>

<?php if (empty($_SESSION['user_id'])) : ?>

    <a href="/signup">Sign up for an API key</a>

    or 

    <a href="/login">log in</a>

<?php else: ?>

    <a href="/profile">View profile</a>

    or

    <a href="/logout">log out</a>

<?php endif; ?>