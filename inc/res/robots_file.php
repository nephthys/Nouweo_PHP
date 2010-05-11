<?php
header('Content-Type: text/plain');

// m.nouweo.com/robots.txt (pas indexé)
if (strpos($_SERVER['SERVER_NAME'], 'm.nouweo.com') !== false)
{
	?>
User-agent: *
Disallow: /
	<?php
}
// nouweo.com/robots.txt
else
{
	?>

	<?php
}
?>