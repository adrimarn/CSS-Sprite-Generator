<?php
/**
 * Scandir with recursion option as a parameter
 *
 * @param string $dir_path
 * @param bool $isRecursive
 * @return array
 */
function my_scandir(string $dir_path, bool $isRecursive = false) : array
{
    global $tree;
    $handle = opendir($dir_path);
    while ($entry = readdir($handle)) {
        if ($entry != "." && $entry != "..") {
            if (is_dir($dir_path . "/" . $entry) && $isRecursive) {
                my_scandir($dir_path . "/" . $entry, $isRecursive);
            } else {
                array_push($tree, $dir_path . "/" . $entry);
                $tree = preg_grep("/^.*\.(png)$/i", $tree);
            }
        }
    }
    closedir($handle);
    return $tree;
}

$tree = [];