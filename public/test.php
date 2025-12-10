<?php
echo "<h1>PHP Works!</h1>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>mod_rewrite loaded: " . (in_array('mod_rewrite', apache_get_modules()) ? 'YES' : 'NO') . "</p>";

echo "<h2>Debug Info:</h2>";
echo "<pre>";
print_r($_GET);
print_r($_SERVER);
echo "</pre>";
