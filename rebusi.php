<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Equation Solver</title>
</head>
<body>
    <h1>Equation Solver</h1>
    <form method="post" action="">
        <label for="num1">Первое слагаемое:</label>
        <input type="text" id="num1" name="num1" required><br>
 
        <label for="num2">Второе слагаемое:</label>
        <input type="text" id="num2" name="num2" required><br>
 
        <label for="result">Результат:</label>
        <input type="text" id="result" name="result" required><br>
 
        <input type="submit" value="Решить">
    </form>
 
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num1 = $_POST["num1"];
        $num2 = $_POST["num2"];
        $result = $_POST["result"];
 
        findEquation($num1, $num2, $result);
    }
 
    function findEquation($num1, $num2, $result)
    {
        $digits = array_unique(mb_str_split($num1 . $num2 . $result));
        $numDigits = count($digits);
 
        if ($numDigits > 10) {
            echo "<p>Решение не найдено</p>";
            return;
        }
 
        $permutations = generatePermutations(range(0, 9), $numDigits);
 
        foreach ($permutations as $perm) {
            $map = array_combine($digits, $perm);
            $n1 = translateNumber($num1, $map);
            $n2 = translateNumber($num2, $map);
            $res = translateNumber($result, $map);
 
            if ($n1 + $n2 == $res) {
                echo "<p>$num1 = $n1, $num2 = $n2, $result = $res</p>";
                return;
            }
        }
 
        echo "<p>Решение не найдено</p>";
    }
 
    function generatePermutations($items, $length)
    {
        if ($length == 1) {
            return array_map(function ($item) {
                return [$item];
            }, $items);
        }
 
        $permutations = [];
        foreach ($items as $item) {
            $remaining = array_diff($items, [$item]);
            $perms = generatePermutations($remaining, $length - 1);
            foreach ($perms as $perm) {
                $permutations[] = array_merge([$item], $perm);
            }
        }
 
        return $permutations;
    }
 
    function translateNumber($number, $map)
    {
        $digits = mb_str_split($number);
        $translated = '';
        foreach ($digits as $digit) {
            $translated .= $map[$digit];
        }
 
        return (int)$translated;
    }
 
    function mb_str_split($string)
    {
        return preg_split('/(?<!^)(?!$)/u', $string);
    }
    ?>
</body>
</html>