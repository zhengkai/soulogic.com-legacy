<?php
require_once __DIR__."/func.inc.php";
require_once __DIR__."/data.inc.php";

require_once __DIR__."/header.inc.php";
register_shutdown_function(function() {
	require_once __DIR__."/footer.inc.php";
});
