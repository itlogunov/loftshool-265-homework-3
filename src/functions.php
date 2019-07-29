<?php

/**
 * Задание #1
 *
 * @param $fileName
 * @return string
 */
function task1($fileName): string
{
    $xml = simplexml_load_file($fileName);
    $return = '';

    foreach ($xml->attributes() as $attribute) {
        $return .= $attribute->getName() . ': ' . $attribute . '<br>';
    }

    $return .= '<br>';

    foreach ($xml->Address as $item) {
        $return .= $item->getName() . '<br>';
        $return .= 'Type: ' . $item->attributes()->Type . '<br>';
        $return .= 'Name: ' . $item->Name->__toString() . '<br>';

        $address = [
            $item->Street->__toString(),
            $item->City->__toString(),
            $item->State->__toString(),
            $item->Zip->__toString(),
            $item->Country->__toString()
        ];
        $return .= implode(', ', $address) . '<br><br>';
    }

    $return .= 'DeliveryNotes: ' . $xml->DeliveryNotes->__toString() . '<br><br>';

    foreach ($xml->Items->Item as $product) {
        $return .= 'PartNumber: ' . $product->attributes()->PartNumber . '<br>';
        $return .= 'ProductName: ' . $product->ProductName->__toString() . '<br>';
        $return .= 'Quantity: ' . $product->Quantity->__toString() . ' pc<br>';
        $return .= 'Price ' . $product->USPrice->__toString() . '$' . '<br>';
        $return .= 'Comments: ' . $product->Comment->__toString() . '<br>';

        $shippingDate = $product->ShipDate->__toString();
        if ($shippingDate) {
            $return .= 'Shipping Date: ' . $shippingDate . '<br>';
        }

        $return .= '<br>';
    }

    return $return;
}

/**
 * Задание #2
 * @param array $arrData
 */
function task2(array $arrData)
{
    $outputFileName = 'output.json';
    file_put_contents($outputFileName, json_encode($arrData));

    $arrUsers = json_decode(file_get_contents($outputFileName), true);
    $change = mt_rand(0, 1);
    if ($change) {
        $arrProfessionList = ['developer', 'lawyer', 'actor', 'architect', 'astronomer', 'librarian', 'driver'];
        foreach ($arrUsers as $key => $user) {
            $arrUsers[$key] = [
                'age' => mt_rand(23, 30),
                'profession' => $arrProfessionList[mt_rand(0, 5)]
            ];
        }
    }

    $outputFileName2 = 'output2.json';
    file_put_contents($outputFileName2, json_encode($arrUsers));

    $arrUsers1 = json_decode(file_get_contents($outputFileName), true);
    $arrUsers2 = json_decode(file_get_contents($outputFileName2), true);

    echo 'Первый массив:<br>';
    dump($arrUsers1);
    echo 'Второй массив:<br>';
    dump($arrUsers2);
    echo '<br>';

    $arrDiff = arrayDiffAssocRecursive($arrUsers1, $arrUsers2);
    if ($arrDiff) {
        echo 'Данные первого массива отличаются от второго:<br>';
        dump($arrDiff);
    } else {
        echo 'Различия массивов не найдены<br><br>';
    }
}

/**
 * Задание #3
 *
 * @param int $amount
 * @param string $fileName
 * @return int
 */
function task3(int $amount, string $fileName = 'numbers.csv'): int
{
    $arrNumbers = [];
    for ($i = 0; $i <= $amount; $i++) {
        $arrNumbers[] = [mt_rand(1, 100)];
    }

    $handle = fopen($fileName, 'w');
    if (!$handle) {
        echo 'Ошибка создания файла ' . $fileName;
        return false;
    }

    foreach ($arrNumbers as $value) {
        fputcsv($handle, $value, ',');
    }

    fclose($handle);

    $sumEvenNumbers = 0;
    $handle = fopen($fileName, 'r');
    while ($str = fgetcsv($handle, 10000, ',')) {
        $value = (int)$str[0];
        if ($value % 2 == 0) {
            $sumEvenNumbers += $value;
        }
    }

    return $sumEvenNumbers;
}

/**
 * Задание #4
 *
 * @param string $url
 * @return array
 */
function task4(string $url): array
{
    $arrData = [];
    $pageData = json_decode(file_get_contents($url));
    foreach ($pageData->query->pages as $page) {
        $arrData[] = [
            'id' => (int)$page->pageid,
            'title' => (string)$page->title
        ];
    }

    return $arrData;
}

/**
 * Вывод переменной в удобном виде
 *
 * @param $var
 */
function dump($var)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}

/**
 * Рекурсивный поиск различий в массиве
 *
 * @param $array1
 * @param $array2
 * @return array
 */
function arrayDiffAssocRecursive($array1, $array2): array
{
    $arrDifference = [];
    foreach ($array1 as $key => $value) {
        if (is_array($value)) {
            if (!isset($array2[$key]) || !is_array($array2[$key])) {
                $arrDifference[$key] = $value;
            } else {
                $newDiff = arrayDiffAssocRecursive($value, $array2[$key]);
                if (!empty($newDiff)) {
                    $arrDifference[$key] = $newDiff;
                }
            }
        } else if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
            $arrDifference[$key] = $value;
        }

    }

    return $arrDifference;
}