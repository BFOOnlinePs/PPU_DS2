<?php
    header("Content-type: text/css; charset: UTF-8");
    use App\Models\SystemSetting;
    $systemSetting = SystemSetting::first();
    $color_background = $systemSetting->ss_primary_background_color;
    $color_text = $systemSetting->ss_primary_font_color;
?>
<style>
    .bg-primary ,
    .btn-primary ,
    .nav-link:hover,
    .radio-primary input[type="radio"]:checked + label::after,
    .radio-primary input[type="radio"] + label::after,
    .switch input:checked + .switch-state,
    .select2-container--default .select2-selection--multiple .select2-selection__choice,
    .ribbon-primary
    {
{{--        <?php echo $color_background; ?> !important;--}}
        color: <?php echo $color_text; ?> !important;
    }
</style>
