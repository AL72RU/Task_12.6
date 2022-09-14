<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


function getPartsFromFullname($fullName) {
    $pieces = explode(' ', $fullName);
    $array = [
        'surname' => "$pieces[0]",
        'name' => "$pieces[1]",
        'patronomyc' => "$pieces[2]",
    ];
    return $array;
}


function getFullnameFromParts($surname, $name, $patronomyc) {
    $fullName = $surname." ".$name." ".$patronomyc;
    return $fullName;
}


function getShortName($string) {
    $arr = getPartsFromFullname($string);
    $surname = mb_strimwidth($arr['surname'], 0, 1);
    return ($arr['name']." ".$surname.".");
}


function getGenderFromName($string) {
    $gen = 0;
    $arr = getPartsFromFullname($string);
    $patronomyc = $arr['patronomyc'];
    if ((substr($arr['patronomyc'], -3*2, 3*2)) === "вна") $gen -= 1;
    if ((substr($arr['patronomyc'], -1*2, 1*2)) === "а") $gen -= 1;
    if ((substr($arr['patronomyc'], -2*2, 2*2)) === "ва") $gen -= 1;
    if ((substr($arr['patronomyc'], -2*2, 2*2)) === "ич") $gen += 1;
    if ((substr($arr['patronomyc'], -1*2, 1*2)) === "н") $gen += 1;
    if ((substr($arr['patronomyc'], -1*2, 1*2)) === "в") $gen += 1;

    return (int)$gen;
}


function getGenderDescription($array) {
    $x = 0;
    $y = 0;
    $n = 0;
    for ($i=0; $i < count($array); $i++) { 
        if (((int)getGenderFromName($array[$i]['fullname'])) <= -1) $x += 1;
        if (((int)getGenderFromName($array[$i]['fullname'])) >= 1) $y += 1;
        if (((int)getGenderFromName($array[$i]['fullname'])) == 0) $n += 1;
    }
    $x = round($x*100/count($array), 1);
    $y = round($y*100/count($array), 1);
    $n = round($n*100/count($array), 1);
    return "Гендерный состав аудитории:<br>\n---------------------------<br>\nМужчины - $y%<br>Женщины - $x%<br>\nНе удалось определить - $n%";
}



function getPerfectPartner($surname, $name, $patronomyc, $array) {
    $surname = mb_convert_case($surname, MB_CASE_TITLE, "UTF-8");
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE, "UTF-8");
    $fullName = getFullnameFromParts($surname, $name, $patronomyc);
    $gen = 0;
    if ((int)getGenderFromName($fullName) <= -1) $gen = -1;
    if ((int)getGenderFromName($fullName) >= 1) $gen = 1;
    $randFullName = "";
    do {
        $randFullName = $array[random_int(0, count($array)-1)]['fullname'];
        $randGen = 0;
        if ((int)getGenderFromName($randFullName) <= -1) $randGen = -1;
        if ((int)getGenderFromName($randFullName) >= 1) $randGen = 1;
    } while ($gen === $randGen);
    return getShortName($fullName)." + ".getShortName($randFullName)." =<br>\n"."♡ Идеально на ".round(50+mt_rand()/mt_getrandmax()*(50), 2)."% ♡";
}


?>