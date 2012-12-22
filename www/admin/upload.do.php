<?php
require_once(dirname(dirname(__DIR__))."/inc/upload.php");

$oUpload = new Upload("file");

$aTango["file"] = $oUpload->get();
