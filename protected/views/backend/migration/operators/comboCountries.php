<?php
$options = '';
$hasFree = false;
$selected = isset($selected) ? $selected : 0;

foreach( $countries as $id => $country ){
    $style = '';

    if( !$country->directory_id ){
        $style = 'style="color:red;"';
        $hasFree = true;
    }

    $sel = '';
    if( $selected == $country->element_id ){
        $sel = ' selected="selected"';
    }

    $options .= '<option value="' . $country->element_id . '" ' . $sel . $style . '>' . $country->name . '</option>';
}?>

<select id="tCountriesId" class="form-control input-sm <?php echo ( $hasFree ? 't-el-error' : '' );?>" >
    <?php echo $options; ?>
</select><?