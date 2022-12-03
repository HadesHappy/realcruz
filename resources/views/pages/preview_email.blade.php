<?php
$css = file_get_contents(public_path().'/core/css/email.css');
?>

		<style>
			{!! $css !!}
		</style>
		{!! $page->content !!}

