<?php
$options = '';
$hasFree = false;
$selected = isset($selected) ? $selected : 0;

foreach( $resorts as $id => $resort ){
    $style = '';

    if( !$resort->directory_id ){
        $style = 'style="color:red;"';
        $hasFree = true;
    }

    $sel = '';
    if( $selected == $resort->element_id ){
        $sel = ' selected="selected"';
    }

    $options .= '<option value="' . $resort->element_id . '" ' . $sel . $style . '>' . $resort->name . '</option>';
}?>

<select id="tResortsId" class="form-control input-sm <?php echo ( $hasFree ? 't-el-error' : '' );?>" >
    <?php echo $options; ?>
</select><?