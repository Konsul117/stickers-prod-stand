<?php
$dirsToDelete = [];
foreach (glob('../releases/*') as $item) {
    if (is_dir($item)) {
        if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})_(backend|frontend)/', $item, $result)) {
            $dirsToDelete[$result[7]][] = $item;
        }
    }
}


foreach ($dirsToDelete as $dirs) {
    sort($dirs);

    if (count($dirs) > 0) {
        array_pop($dirs);
    }

    foreach ($dirs as $v) {
        shell_exec('rm -rf ./' . $v);
    }
}
