<?php
/*
 * In a true Sator Square, ALL of its words can be read in ALL four of these ways.
If there is any deviation, it would be false to consider it a Sator Square.
 * */

function is_sator_square(array $tablet): bool {
    $temp_hor = [];
    $temp_ver = [];

    foreach ($tablet as $key => $arr) {
        $temp_hor[] = implode("", $arr);
        $temp_ver[] = implode("", array_column($tablet, $key));
    }

    for($i = 0; $i < count($temp_hor); $i++) {
        if(!in_array(strrev($temp_hor[$i]), $temp_hor)) {
            return false;
        }
    }

    for($i = 0; $i < count($temp_ver); $i++) {
        if(!in_array(strrev($temp_ver[$i]), $temp_ver)) {
            return false;
        }
    }

    foreach ($temp_hor as $key => $val) {
        if($val !==  $temp_ver[$key]) {
            return false;
        }
    }

    return true;
}
$arr = [['S', 'A', 'T', 'O', 'R'],
        ['A', 'R', 'E', 'P', 'O'],
        ['T', 'E', 'N', 'E', 'T'],
        ['O', 'P', 'E', 'R', 'A'],
        ['R', 'O', 'T', 'A', 'S']];
echo is_sator_square($arr);