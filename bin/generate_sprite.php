<?php


/**
 * Checks if files passed in parameter are valid PNG images. Else, they are ignored.
 *
 * @param array $files
 * @return array
 */
function checkPNG(array $files): array
{
    global $count_error;
    foreach ($files as $key => $file) {
        if (mime_content_type($file) != 'image/png') {
            echo("\033[31mError : \"$file\" is not a valid PNG file !\033[0m" . PHP_EOL);
            unset($files[$key]);
            $count_error++;
        }
    }
    return $files;
}

/**
 * Get width of sprite that will be generated
 *
 * @param array $files
 * @return int
 */
function get_sprite_width(array $files): int
{
    $width_sprite = 0;
    foreach ($files as $file) {
        $size = getimagesize($file);
        $width_img = $size[0];
        $width_sprite += $width_img;
    }
    return $width_sprite;
}


/**
 * Get height of sprite that will be generated
 *
 * @param array $files
 * @return int
 */
function get_sprite_height(array $files): int
{
    $height_sprite = 0;

    foreach ($files as $file) {
        $size = getimagesize($file);
        $height_img = $size[1];
        if ($height_img > $height_sprite) {
            $height_sprite = $height_img;
        }
    }
    return $height_sprite;
}

/**
 * Initialize sprite with height and width passed in parameter
 *
 * @param int $width_sprite
 * @param int $height_sprite
 * @return resource
 */
function create_sprite(int $width_sprite, int $height_sprite)
{
    if ($width_sprite == 0 || $height_sprite == 0) {
        die('An error occurred while retrieving images, check if folder is not empty.' . PHP_EOL);
    }
    $image = imagecreatetruecolor($width_sprite, $height_sprite);
    imagealphablending($image, true);
    imagesavealpha($image, true);
    $background = imagecolorallocatealpha($image, 0, 0, 0, 127);
    imagefill($image, 0, 0, $background);
    return $image;
}

/**
 * Merge all images into a sprite
 *
 * @param array $files
 * @param string $name_sprite
 * @param string $name_css
 * @param bool $useSprite
 * @return resource
 */
function merge_image(array $files, string $name_sprite, string $name_css, bool $useSprite)
{
    $width_sprite = get_sprite_width($files);
    $height_sprite = get_sprite_height($files);
    $spacing = 0;
    $new = create_sprite($width_sprite, $height_sprite);
    foreach ($files as $file) {

        // Get image size
        $size = getimagesize($file);
        $width = $size[0];
        $height = $size[1];

        // Merge images
        $src = imagecreatefrompng($file);
        imagecopy($new, $src, $spacing, 0, 0, 0, $width, $height);
        $spacing += $width;

        echo "Merging $file to -> $name_sprite" . PHP_EOL;

        // Deleting '.png' extension
        $file_name = substr($file, 0, -4);

        // Adding each image to stylesheet
        if ($useSprite == 1) {
            write_css_spr($name_css, $file_name, $width, $height);
        } else {
            write_css($name_css, $file, $file_name, $width, $height);
        }
    }
    return $new;
}


/**
 * Call functions for running the script.
 *
 * @param string $dir_path
 * @param string $name_sprite
 * @param string $name_css
 * @param bool $isRecursive
 * @param bool $useSprite
 */
function generate_sprite_css(string $dir_path, string $name_sprite,
                             string $name_css, bool $isRecursive, bool $useSprite): void
{
    $files = checkPNG(my_scandir($dir_path, $isRecursive));
    if ($useSprite) {
        create_css_spr($name_css, $name_sprite);
    } else {
        create_css($name_css);
    }

    $image = merge_image($files, $name_sprite, $name_css, $useSprite);
    imagepng($image, $name_sprite);

    global $count_error;
    if ($count_error > 0) {
        echo "\033[33m$count_error file(s) skipped.\033[0m" . PHP_EOL;
    }

    echo "Stylesheet \"$name_css\" ready for use." . PHP_EOL;
}