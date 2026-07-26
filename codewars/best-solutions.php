<?php

/*
 * All my katas are presented here:
 * https://www.codewars.com/users/javadimus/completed
 */

// Name Array Capping
function capMe(array $names): array
{
    return array_map(fn($e) => ucfirst(strtolower($e)), $names);
}

// Growth of a Population
function nbYear(int $p0, int|float $percent, int $aug, int $p): int
{
    $count = 0;
    while ($p0 < $p) {
        $count++;
        $p0 = floor($p0 + ($p0 * $percent) / 100 + $aug);
    }
    return $count;
}

// Drying Potatoes
function potatoes(int $p0, int $w0, int $p1): int
{
    return $w0 * ((100 - $p0)) / ((100 - $p1));
}

// Printer Errors
function printerError(string $s): string
{
    return preg_match_all('/[^a-m]/', $s) . '/' . strlen($s);
}

// Speed Control
function gps(int $s, array $x): int
{
    if (count($x) < 2)
        return 0;

    $speeds = array_map(
        fn($i) => (3600 * ($x[$i + 1] - $x[$i])) / $s,
        range(0, count($x) - 2)
    );

    return floor(max($speeds));
}

// String ends with?
function solution(string $str, string $ending): bool
{
    if ($ending === '')
        return true;
    return substr($str, -strlen($ending)) === $ending;
}

// Correct the date-string
function dateCorrect(null|string $datestring): null|string
{
    if ($datestring === '')
        return '';
    if (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $datestring)) {
        return null;
    }

    $arr = explode('.', $datestring);

    $days = (int) $arr[0];
    $months = (int) $arr[1];
    $years = (int) $arr[2];

    $mon_map = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31
    ];

    while ($months > 12) {
        $months = $months - 12;
        $years++;
    }

    while ($mon_map[$months] < $days) {
        if ($months == 2 && ($years % 4 == 0 && $years % 100 != 0) || ($years % 400 == 0)) {
            $days = $days - 29;
        } else {
            $days = $days - $mon_map[$months];
        }

        $months++;

        if ($months > 12) {
            $months = 1;
            $years++;
        }
    }

    $days = str_pad($days, 2, '0', STR_PAD_LEFT);
    $months = str_pad($months, 2, '0', STR_PAD_LEFT);

    return $days . '.' . $months . '.' . $years;
}

// Easy wallpaper
function wallPaper($l, $w, $h): string
{
    $numbers = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen', 'twenty'];
    $walls_square = ($l * $h + $w * $h) * 2;
    $rolls_square = 0.52 * 10;
    $res = $walls_square / $rolls_square + $walls_square / $rolls_square * 0.15;

    return $l > 0 && $res <= 20 ? $numbers[ceil($res)] : 'zero';
}

// All Inclusive?
function containAllRots(string $s, array $arr): bool
{
    if ($s === '') {
        return true;
    }

    $arr_str = str_split($s);
    $arr_fix = [];

    for ($i = 0; $i < count($arr_str); $i++) {
        $arr_fix[$i] = $arr_str;
        array_push($arr_str, $arr_str[$i]);
        unset($arr_str[$i]);
        $arr_fix[$i] = join($arr_fix[$i]);

        if (!in_array($arr_fix[$i], $arr)) {
            return false;
        }
    }

    return true;
}

// Composing squared strings
function compose(string $s1, string $s2): string
{
    $arr1 = explode("\n", $s1);
    $arr2 = explode("\n", $s2);
    $n = count($arr1);
    $result = [];

    for ($i = 0; $i < $n; $i++) {
        $left = substr($arr1[$i], 0, $i + 1);
        $right = substr($arr2[$n - 1 - $i], 0, $n - $i);
        $result[] = $left . $right;
    }

    return implode("\n", $result);
}

// Scaling Squared Strings
function scale(string $s, int $k, int $n): string
{
    if ($s === '') {
        return '';
    }

    $str = '';

    foreach (explode("\n", $s) as $el) {
        $str .= str_repeat(join(array_map(function ($sub) use ($k) {
            return str_repeat($sub, $k);
        },
            str_split($el))) . "\n", $n);
    }

    return trim($str, "\n");
}

// How Green Is My Valley?
function makeValley(array $a): array
{
    if (count($a) === 0) {
        return [];
    }

    rsort($a);

    $bottom = $a[count($a) - 1];
    $left_win = [];
    $right_win = [];

    for ($i = 0; $i < count($a) - 1; $i++) {
        if ($i % 2 === 0) {
            $left_win[] = $a[$i];
        } else {
            $right_win[] = $a[$i];
        }
    }

    sort($right_win);
    $left_win[] = $bottom;

    return array_merge($left_win, $right_win);
}

// A Rule of Divisibility by 7
function seven(int $m): array
{
    $count = 0;
    while ($m >= 100) {
        $count++;
        $m = intdiv($m, 10) - (2 * ($m - intdiv($m, 10) * 10));
    }
    return [$m, $count];
}

// Rotate for a Max
function maxRot(int $n): int
{
    $arr[] = $n;
    $res = str_split(strval($n));

    for ($i = 0; $i < count($res) - 1; $i++) {
        $repl = implode('', array_slice($res, $i, 1));
        unset($res[$i]);
        array_push($res, $repl);
        $res = array_values($res);
        $arr[] = implode('', $res);
    }

    return max($arr);
}

// Going to the cinema
function movie(int $card, int $ticket, float $perc): int
{
    $count = 0;
    $sys_a = 0;

    $sys_b = $card;
    $sys_b_acc = $ticket * $perc;

    while ($sys_a <= ceil($sys_b)) {
        $count++;

        $sys_a += $ticket;

        $sys_b += $sys_b_acc;
        $sys_b_acc *= $perc;
    }

    return $count;
}

// Mumbling
function accum(string $s): string
{
    $arr = str_split(strtolower($s));

    return implode('-', array_map(function ($key, $el) {
        return ucfirst(str_repeat($el, $key + 1));
    }, array_keys($arr), $arr));
}

// Count the Digit
function nbDig(int $n, int $d): int
{
    return array_reduce(range(0, $n), function ($carry, $item) use ($d) {
        $carry += preg_match_all('/' . $d . '/', strval($item ** 2));
        return $carry;
    }, 0);
}

// Maximum Length Difference
function mxdiflg(mixed $a1, mixed $a2): mixed
{
    if (empty($a1) || empty($a2)) {
        return -1;
    }

    $a1_fix = array_map(fn($e) => strlen($e), $a1);
    $a2_fix = array_map(fn($e) => strlen($e), $a2);

    sort($a1_fix);
    sort($a2_fix);

    $diff1 = abs($a1_fix[0] - $a2_fix[count($a2_fix) - 1]);
    $diff2 = abs($a1_fix[count($a1_fix) - 1] - $a2_fix[0]);

    return max($diff1, $diff2);
}

// Leap Years
function isLeapYear(int $year): bool
{
    return ($year % 4 === 0 && $year % 100 !== 0) || $year % 400 === 0;
}

// Euclidean distance in n dimensions
function euclidean_distance(array $a, array $b): float
{
    $res = array_reduce(array_keys($a), function ($carry, $key) use ($a, $b) {
        $carry += pow($a[$key] - $b[$key], 2);
        return $carry;
    }, 0);

    return round(sqrt($res), 2);
}

// Friday the 13th Part 1
function killcount(array $counselors, int $jason): array
{
    $filter = array_filter($counselors,
        function ($item) use ($jason) {
            return $item[1] < $jason;
        });
    return array_map(fn($el) => $el[0], array_values($filter));
}

// Naughty or Nice?
function what_list_am_i_on(array $actions): string
{
    $arr = array_map(fn($e) => in_array($e[0], ['b', 'f', 'k'])
        ? 'naughty'
        : (in_array($e[0], ['g', 's', 'n'])
            ? 'nice'
            : ''), $actions);

    $arr_count = array_count_values($arr);

    if (isset($arr_count['nice']) === false) {
        return 'naughty';
    }

    if (isset($arr_count['naughty']) === false) {
        return 'nice';
    }

    return $arr_count['naughty'] >= $arr_count['nice'] ? 'naughty' : 'nice';
}

// Hit Count
function counter_effect(string $hit_count): array
{
    return array_map(fn($e) => range(0, intval($e)), str_split($hit_count));
}

// Resistor Color Codes
function decodeResistorColors(string $bands): string
{
    $color_map = [
        'black' => 0,
        'brown' => 1,
        'red' => 2,
        'orange' => 3,
        'yellow' => 4,
        'green' => 5,
        'blue' => 6,
        'violet' => 7,
        'gray' => 8,
        'white' => 9,
        'gold' => 0.05,
        'silver' => 0.1,
    ];

    $bands_arr = explode(' ', $bands);

    $resistance = $color_map[$bands_arr[0]] . $color_map[$bands_arr[1]] . str_repeat('0', $color_map[$bands_arr[2]]);

    $deviation = count($bands_arr) > 3 ? $color_map[$bands_arr[3]] * 100 : 20;

    $resistance = $resistance >= 1000 && $resistance < 1000000
        ? $resistance / 1000 . 'k'
        : ($resistance >= 1000000
            ? $resistance / 1000000 . 'M'
            : $resistance);

    return $resistance . ' ohms, ' . $deviation . '%';
}

// Word values
function word_value(array $a): array
{
    $arr = [];

    for ($i = 0; $i < count($a); $i++) {
        $arr[] = array_reduce(str_split(str_replace(' ', '', $a[$i])),
            fn($el, $s) => $el += (ord($s) - 96), 0);
    }
    $arr_fix = array_map(fn($x) => ($x + 1) * $arr[$x], array_keys($arr));

    return $arr_fix;
}

// Easy Line
function easyline(int $n): int
{
    $arr = [];

    for ($start = 1, $i = 0; $i < $n; $i++) {
        $start = $start * (($n - $i) / ($i + 1));
        $arr[] = $start * $start;
    }
    array_unshift($arr, 1);

    return round(log(array_sum($arr)));
}

// Simple string reversal II
function solve(string $str, int $a, int $b): string
{
    $substr = substr($str, $a, $b - ($a - 1));
    $begin = substr($str, 0, $a);
    $end = substr($str, $b + 1);

    return $begin . strrev($substr) . $end;
}

// Validate a PIN code
function validatePin(string $pin): bool
{
    if (strlen($pin) !== 4 && strlen($pin) !== 6 || !ctype_digit($pin)) {
        return false;
    }

    return true;
}

// draw me a chessboard
function chessBoard(int $rows, int $columns): array
{
    $arr = [];

    for ($i = 0; $i < $rows; $i++) {
        $arr[$i] = array_map(fn($e) => ($e + $i) % 2 === 0 ? 'O' : 'X', range(0, $columns - 1));
    }

    return $arr;
}

// Search for letters
function change(string $string): string
{
    $str = strtolower($string);
    $arr = range('a', 'z');
    $res = str_repeat('0', 26);
    for ($i = 0; $i < strlen($str); $i++) {
        if (in_array($str[$i], $arr)) {
            $id = array_search($str[$i], $arr);
            $res[$id] = '1';
        }
    }
    return $res;
}

// Parts of a list
function partlist(array $arr): array
{
    $arr_temp = [];
    for ($i = 1; $i < count($arr); $i++) {
        $arr_temp[][] = implode(' ', array_slice($arr, 0, $i));
        $arr_temp[$i - 1][] = implode(' ', array_slice($arr, $i));
    }

    return $arr_temp;
}

// Average Array
function avgArray(array $arr): array
{
    $arr_fix = [];
    $arr_res = [];

    for ($i = 0; $i < count($arr[0]); $i++) {
        $arr_fix[] = array_column($arr, $i);
    }

    for ($i = 0; $i < count($arr_fix); $i++) {
        $arr_res[] = array_sum($arr_fix[$i]) / count($arr_fix[$i]);
    }

    return $arr_res;
}

// First Fibonacci

function solution(int $first, int $second): array
{
    $arr = [];
    $arr = [$second, $afirst];
    $i = 0;

    while ($arr[$i] - $arr[$i + 1] >= 0) {
        $arr[] = $arr[$i] - $arr[$i + 1];
        $i++;
    }

    return [$arr[count($arr) - 2], $arr[count($arr) - 3]];
}

// Excel sheet column numbers

function titleToNumber(string $title): int
{
    $arr = range('A', 'Z');
    $pos = array_search($title[0], $arr) + 1;

    for ($i = 1; $i < strlen($title); $i++) {
        $pos = 26 * $pos + array_search($title[$i], $arr) + 1;
    }

    return $pos;
}

// Band name generator

function band_name_generator(string $s): string
{
    if ($s[0] === $s[strlen($s) - 1]) {
        $res = ucfirst($s) . substr($s, 1);
    } else {
        $res = 'The ' . ucfirst($s);
    }

    return $res;
}

// Double Trouble

function trouble(array $x, int $t): array
{
    for ($i = 0; $i < (count($x) - 1); $i++) {
        if ($x[$i] + $x[$i + 1] === $t) {
            unset($x[$i + 1]);
            $x = array_values($x);
            $i--;
        }
    }

    return $x;
}

// Can Santa save Christmas?

function determine_time(array $durations): bool
{
    $hours = $mins = $secs = [];

    if ($durations === []) {
        return true;
    }

    foreach ($durations as $unit) {
        foreach (explode(':', $unit) as $key => $bit) {
            if ($key === 0) {
                $hours[] = (int) $bit;
            }

            if ($key === 1) {
                $mins[] = (int) $bit;
            }

            if ($key === 2) {
                $secs[] = (int) $bit;
            }
        }
    }

    $s_res = array_sum($secs) % 60;
    $m_res = (array_sum($mins) + intdiv(array_sum($secs), 60)) % 60;
    $h_res = array_sum($hours) + intdiv(array_sum($mins) + intdiv(array_sum($secs), 60), 60);

    if ($h_res > 24 || $h_res === 24 && ($m_res > 0 || $s_res > 0)) {
        return false;
    }

    return true;
}

// Convert a linked list to a string

function stringify($list): string
{
    $str = '';
    if (!is_null($list)) {
        foreach ($list as $value) {
            if (is_int($value)) {
                $str .= strval($value) . ' -> ';
            } else {
                return $str .= stringify($value);
            }
        }
    } else {
        $str .= 'NULL';
    }
    return $str;
}

// Sort Out The Men From Boys

function menFromBoys(array $arr): array
{
    $arr = array_unique($arr);

    $odds = array_filter($arr, fn($n) => $n & 1);
    $evens = array_diff($arr, $odds);

    sort($odds);
    sort($evens);

    return array_merge($evens, array_reverse($odds));
}

// Build a square
function generateShape(int $n): string
{
    return rtrim(str_repeat(str_repeat('+', $n) . PHP_EOL, $n));
}

// Bubblesort Once
function bubblesort_once(array $a): array
{
    $arr = $a;
    foreach ($arr as $k => $val) {
        if ($k < count($arr) - 1) {
            if ($arr[$k] > $arr[$k + 1]) {
                $el = array_slice($arr, $k, 1);
                array_splice($arr, $k, 1);
                array_splice($arr, $k + 1, 0, $el[0]);
            }
        }
    }
    return $arr;
}

// Function 1 - hello world

define('WELCOME', 'hello world!');
eval('function greet() { return WELCOME; }');

// Is he gonna survive?

function hero(int $bullets, int $dragons): bool
{
    if ($dragons === 0) {
        return true;
    }

    $mission = floor($bullets / $dragons);

    return $mission >= 2;
}

// Basic Mathematical Operations

function basicOp(string $op, int $val1, int $val2): int|string
{
    $result = match ($op) {
        '+' => $val1 + $val2,
        '-' => $val1 - $val2,
        '*' => $val1 * $val2,
        '/' => $val1 / $val2,
        default => 'Invalid operator!'
    };

    return $result;
}

// Opposites Attract

function lovefunc(int $flower1, int $flower2): bool
{
    if (($flower1 % 2 !== 0 && $flower2 % 2 === 0) || ($flower1 % 2 === 0 && $flower2 % 2 !== 0)) {
        return true;
    }

    return false;
}

// Is n divisible by x and y?

function is_divisible(int $n, int $x, int $y): bool
{
    return $n % $x === 0 && $n % $y === 0;
}

// Triple Trouble

function triple_trouble(string $one, string $two, string $three): string
{
    $str = '';

    for ($i = 0; $i < strlen($one); $i++) {
        $str .= $one[$i] . $two[$i] . $three[$i];
    }

    return $str;
}

// Will there be enough space?

function enough(int $cap, int $on, int $wait): int
{
    return ($cap - $on) >= $wait ? 0 : abs($cap - $on - $wait);
}

// I love you, a little , a lot, passionately ... not at all

function how_much_i_love_you(int $nb_petals): string
{
    $petals = ['I love you', 'a little', 'a lot', 'passionately', 'madly', 'not at all'];

    if ($nb_petals > count($petals)) {
        $nb_petals = $nb_petals % count($petals);
    }

    if ($nb_petals === 0) {
        $nb_petals = count($petals);
    }

    return $petals[$nb_petals - 1];
}

// Powers of 2

function powersOfTwo(int $n): array
{
    $arr = [];

    for ($i = 0; $i <= $n; $i++) {
        $arr[] = pow(2, $i);
    }

    return $arr;
}

// Grasshopper - Grade book

function getGrade($a, $b, $c)
{
    $avr = ($a + $b + $c) / 3;

    $score = match (true) {
        90 <= $avr => 'A',
        80 <= $avr => 'B',
        70 <= $avr => 'C',
        60 <= $avr => 'D',
        default => 'F',
    };

    return $score;
}

// Jenny's secret message

function greet($name)
{
    if ($name === 'Johnny') {
        return 'Hello, my love!';
    }

    return "Hello, $name!";
}

// Calculate BMI

function bmi($weight, $height): string
{
    $bmi = $weight / pow($height, 2);

    $res = match (true) {
        $bmi <= 18.5 => 'Underweight',
        $bmi <= 25 => 'Normal',
        $bmi <= 30 => 'Overweight',
        default => 'Obese',
    };

    return $res;
}

echo bmi(85, 1.88);  // Normal

// Fake Binary

function decodeBin($matches)
{
    return $matches[0] < 5 ? '0' : '1';
}

function fake_bin(string $s): string
{
    $pattern = '/[0-9]/i';
    return preg_replace_callback($pattern, 'decodeBin', $s);
}

// Welcome!

function greet(string $language): string
{
    $lang_map = [
        'english' => 'Welcome',
        'czech' => 'Vitejte',
        'danish' => 'Velkomst',
        'dutch' => 'Welkom',
        'estonian' => 'Tere tulemast',
        'finnish' => 'Tervetuloa',
        'flemish' => 'Welgekomen',
        'french' => 'Bienvenue',
        'german' => 'Willkommen',
        'irish' => 'Failte',
        'italian' => 'Benvenuto',
        'latvian' => 'Gaidits',
        'lithuanian' => 'Laukiamas',
        'polish' => 'Witamy',
        'spanish' => 'Bienvenido',
        'swedish' => 'Valkommen',
        'welsh' => 'Croeso',
    ];

    return array_key_exists($language, $lang_map) ? $lang_map[$language] : 'Welcome';
}

// To square(root) or not to square(root)

function map($el)
{
    if (intval(sqrt($el)) * intval(sqrt($el)) == $el) {
        return intval(sqrt($el));
    } else {
        return $el * $el;
    }
}

function squareOrSquareRoot(array $array): array
{
    return array_map('map', $array);
}

// Simple Fun #1: Seats in Theater

function seatsInTheater(int $nCols, int $nRows, int $col, int $row): int
{
    return ($nCols - $col + 1) * ($nRows - $row);
}

// 101 Dalmatians - squash the bugs, not the dogs!

function howManyDalmations(int $number): string
{
    $dogs = ['Hardly any', 'More than a handful!', "Woah that's a lot of dogs!", '101 DALMATIANS!!!'];

    $respond = $number <= 10 ? $dogs[0] : ($number <= 50 ? $dogs[1] : ($number == 101 ? $dogs[3] : $dogs[2]));

    return $respond;
}

// Fuel Calculator: Total Cost

function fuel_price(int $litres, float $price_per_litre): float
{
    $arr = range(1, $litres);
    $discount = 0;

    foreach ($arr as $value) {
        if ($value % 2 === 0) {
            $discount += 0.05;
        }
    }
    if ($discount > 0.25) {
        $discount = 0.25;
    }

    $total = $price_per_litre * $litres;
    $bonus = $discount * $litres;

    return $total - $bonus;
}

// Is your period late?

function periodIsLate(DateTime $last, DateTime $today, int $cycleLength): bool
{
    return date_diff($today, $last)->format('%a') > $cycleLength;
}

// Total amount of points

function points(array $games): int
{
    $arr_win = array_filter($games, function ($n) {
        $first = substr($n, 0, 1);
        $last = substr($n, -1);
        return $first > $last;
    });
    $arr_draw = array_filter($games, function ($n) {
        $first = substr($n, 0, 1);
        $last = substr($n, -1);
        return $first === $last;
    });
    $score = count($arr_win) * 3 + count($arr_draw);

    return $score;
}

// Twice as old

function twice_as_old(int $dad_years_old, int $son_years_old): int
{
    $counter = 0;

    if ($son_years_old > 0 && $son_years_old > 0 && $dad_years_old / $son_years_old === 2) {
        return 0;
    }

    if ($son_years_old === 0) {
        return $dad_years_old;
    }

    $top_bottom = $dad_years_old / $son_years_old;

    for ($i = 0; $i < 100; $i++) {
        if ($top_bottom > 2) {
            if (($dad_years_old + $i) / ($son_years_old + $i) === 2) {
                $counter = $i;
            }
        } else {
            if ($son_years_old !== $i) {
                if (($dad_years_old - $i) / ($son_years_old - $i) === 2) {
                    $counter = $i;
                }
            }
        }
    }

    return $counter;
}

// Find numbers which are divisible by given number

function divisibleBy(array $numbers, int $divisor): array
{
    return array_values(array_filter($numbers, fn($n) => ($n % $divisor) === 0));
}
