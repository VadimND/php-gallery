<?php
/*
All my kata are presented here:
https://www.codewars.com/users/javadimus/completed
*/
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

function twice_as_old(int $dad_years_old, int $son_years_old) : int {
  $counter = 0;

  if ($son_years_old > 0 && $son_years_old > 0 && $dad_years_old / $son_years_old === 2) return 0;

  if($son_years_old === 0) {
    return $dad_years_old;
  }

  $top_bottom = $dad_years_old / $son_years_old;

  for ($i = 0; $i < 100; $i++) {
     if ($top_bottom > 2) {
        if (($dad_years_old + $i) / ($son_years_old + $i) === 2) {
             $counter = $i;
        }
     }
     else {
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

function divisibleBy(array $numbers, int $divisor) : array {
  return array_values(array_filter($numbers, fn($n) => ($n % $divisor) === 0));
}