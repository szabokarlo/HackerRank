<?php

declare(strict_types=1);

function getInput(): array
{
    $input = "5 3 * 2 +";

    //$input = file_get_contents("php://stdin");

    return empty($input)
        ? []
        : explode(' ', $input);
}

function getSubResult(int $number1, int $number2, string $operand): int
{
    switch ($operand)
    {
        case '+':
            return $number1 + $number2;
            break;
        case '-':
            return $number1 - $number2;
            break;
        case '/':
            return $number1 / $number2;
            break;

        case '*':
            return $number1 * $number2;
            break;
        default:
            throw new InvalidArgumentException();
            break;
    }
}

function getResult(array $elements): int
{
    if (empty($elements))
    {
        throw new InvalidArgumentException();
    }

    do
    {
        if (count($elements) < 3)
        {
            throw new InvalidArgumentException();
        }

        $number1 = getValidatedInteger((string)$elements[0]);
        $number2 = getValidatedInteger((string)$elements[1]);
        $operand = (string)$elements[2];

        $result = getSubResult($number1, $number2, $operand);

        $elements = array_slice($elements, 3);

        array_unshift($elements, $result);
    }
    while (count($elements) > 1);

    return (int)array_pop($elements);
}

function getValidatedInteger(string $input): int
{
    $isNumeric = is_numeric($input);

    if (!$isNumeric)
    {
        throw new InvalidArgumentException();
    }

    $integer = filter_var($input, FILTER_VALIDATE_INT);

    if ($integer === false)
    {
        throw new InvalidArgumentException();
    }

    return $integer;
}

function renderResult(int $result)
{
    echo $result;
}

$input = getInput();

$result = getResult($input);

renderResult($result);
