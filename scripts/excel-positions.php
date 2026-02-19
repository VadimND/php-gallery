<?php
/**
 * Given a column title as appear in an Excel sheet, return its corresponding column number.
 */

function titleToNumber(string $title) : int {
  $arr = range('A', 'Z');
  $pos = array_search($title[0], $arr) + 1;

  for($i = 1; $i < strlen($title); $i++) {
     $pos = 26 * $pos + array_search($title[$i], $arr) + 1;
  }

  return $pos;
}

//for example, "AB" -> 28
echo titleToNumber("AB");