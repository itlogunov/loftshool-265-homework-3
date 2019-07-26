<?php

require('src/functions.php');

/**
 * Задание #1
 */
echo task1('data.xml');

/**
 * Задание #2
 */
$arrData = [
    'Джон Траволта' => [
        'age' => 25,
        'profession' => 'actor'
    ],
    'Сара Конор' => [
        'age' => 24,
        'profession' => 'actor'
    ],
];
task2($arrData);

/**
 * Задание #3
 */
$sumEvenNumbers = task3(90, 'numbers.csv');
echo 'Сумма четных чисел = ' . $sumEvenNumbers;

/**
 * Задание 4
 */
$arrPages = task4('https://en.wikipedia.org/w/api.php?action=query&titles=Main%20Page&prop=revisions&rvprop=content&format=json');
foreach ($arrPages as $arrPage) {
    echo 'pageId = ' . $arrPage['id'] . ', title = ' . $arrPage['title'] . '<br>';
}
