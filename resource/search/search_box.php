<?php
function echoSearchBox($width, $input_id, $input_name, $button_type)
{
echo '<div style="width: '.$width.'px;padding: 10px 10px 10px 10px;">
<div class="input-group">
<input type="text" class="form-control" placeholder="搜索..." id="'.$input_id.'" name="'.$input_name.'">
<span class="input-group-btn">
<button id="search_button" class="btn btn-default" type="'.$button_type.'">
<img style="width:15px;height:15px;" src="plugin/glyphicons_free/glyphicons/png/glyphicons-28-search.png"/>
</button>
</span>
</div>
</div>';
}
?>
