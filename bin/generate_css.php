<?php

/**
 * Create or reset the stylesheet file
 *
 * @param string $name_css
 */
function create_css(string $name_css): void
{
    $handle = fopen(($name_css), "w");
    fclose($handle);
}

/**
 * Add images to the stylesheet
 * @param string $name_css
 * @param string $file
 * @param string $file_name
 * @param int $width
 * @param int $height
 */
function write_css(string $name_css, string $file, string $file_name, int $width, int $height): void
{
    $handle = fopen(($name_css), "a");
    fwrite($handle, ".sprite-" . basename($file_name) . " {
        width: $width" . "px;
        height: $height" . "px;
        background-image: url('$file');
        background-repeat: no-repeat;
        display: block;
        }\n\n");
    fclose($handle);
}

/**
 * Create / Reset the stylesheet using the generated sprite
 *
 * @param string $name_css
 * @param string $name_sprite
 */
function create_css_spr(string $name_css, string $name_sprite): void
{
    $handle = fopen(($name_css), "w");
    fwrite($handle, ".sprite {
    background-image: url($name_sprite);
    background-repeat: no-repeat;
    display: block;
    } \n\n");
    fclose($handle);
}

/**
 * Written in the stylesheet using the generated sprite.
 *
 * @param string $name_css
 * @param string $file_name
 * @param int $width
 * @param int $height
 */
function write_css_spr(string $name_css, string $file_name, int $width, int $height): void
{
    static $position = 0;
    $handle = fopen(($name_css), "a");
    fwrite($handle, ".sprite-" . basename($file_name) . " {
        width: $width" . "px;
        height: $height" . "px;
        background-position: -" . "$position" . "px -0" . "px;
        }\n\n");
    $position += $width;
    fclose($handle);
}