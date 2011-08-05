<?php
$aTango["menu"] = 100;

$aArchive =& $aTango["archive"];

$bEdit = !empty($aArchive["id"]);
?>

<form class="compose" method="POST" action="compose.do.php">
<?php
if ($bEdit) {
	?>
	<input type="hidden" name="archive_id" value="<?php echo $aArchive["id"]; ?>" />
	<?php
}
?>
<input type="hidden" name="original_tag" value="" />
<input type="hidden" name="delete_tag" value="" />
<table>
	<tr>
		<td>标题</td>
		<td><input type="text" name="title" value="<?php echo $aArchive["title"]; ?>" /></td>
	</tr>
	<tr>
		<td>副标题</td>
		<td><input type="text" name="subtitle" value="<?php echo $aArchive["subtitle"]; ?>" /></td>
	</tr>
	<tr>
		<td>分类</td>
		<td><select name="folder"><option>请选择</option><?php
$aCategory = BlogMenu::getCategory();
foreach ($aCategory as $iCategory => $aRow) {
	echo "<option value=\"".$iCategory."\"".($aArchive["folder"] == $iCategory ? " selected=\"true\"" : "").">";
	echo $aRow["name"].(!empty($aRow["hide"]) ? "（已废弃）" : "");
	echo "</option>";
}
		?></select></td>
	</tr>
	<tr>
		<td>作者</td>
		<td><input type="text" name="author" value="<?php echo $aArchive["author"]; ?>" /></td>
	</tr>
	<tr>
		<td>出处</td>
		<td><input type="text" name="provenance_text" value="<?php echo $aArchive["provenance_text"]; ?>" /></td>
	</tr>
	<tr>
		<td>出处 URL</td>
		<td><input type="text" name="provenance_url" value="<?php echo $aArchive["provenance_url"]; ?>" /></td>
	</tr>
	<tr>
		<td>是否原创</td>
		<td><label><input type="checkbox" name="original_tag" value="Y"<?php if ($aArchive["original_tag"]) { echo " checked=\"true\""; } ?> />原创</label></td>
	</tr>
	<tr>
		<td>是否删除</td>
		<td><label><input type="checkbox" name="delete_tag" value="Y"<?php if ($aArchive["delete_tag"]) { echo " checked=\"true\""; } ?> />删除</label></td>
	</tr>
	<tr>
		<td>内容</td>
		<td><textarea name="content"><?php echo $aArchive["preview"]; ?></textarea></td>
	</tr>
</table>

<p><button type="submit"><?php echo $bEdit ? "修改" : "发布"; ?>文章</button></p>
</form>
<?php
