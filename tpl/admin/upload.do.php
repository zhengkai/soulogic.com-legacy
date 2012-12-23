<?php
if (empty($aTango["file"])) {

	echo "文件上传失败";

} else {

	echo "文件上传成功，编号 ".$aTango["file"];
}
