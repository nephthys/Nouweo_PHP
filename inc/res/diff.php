<?php
function get_result_diff($vrs1, $vrs2)
{
	include_once('Text/Diff.php');
	include_once('Text/Diff/Renderer/unified.php');

	$lines1 = file($vrs1);
	$lines2 = file($vrs2);

	//die(print_r($lines1));

	$diff = &new Text_Diff($lines1, $lines2);

	$renderer = &new Text_Diff_Renderer_unified();
	return $renderer->render($diff);
}