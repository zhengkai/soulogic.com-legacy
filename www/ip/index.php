<?PHP
$sIP = empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
echo '<p style="font-family: monospace, Microsoft Yahei;">', $sIP, '</p>';
echo '<p style="font-family: monospace, Microsoft Yahei;">ua = ', htmlspecialchars($_SERVER["HTTP_USER_AGENT"]), '</p>';

