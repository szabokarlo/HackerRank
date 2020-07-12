<?php

declare(strict_types=1);

function getInput(): string
{
    $input = "4
0 2 1 0 2
0 1 0 1 2
1 1 0 1 2
0 1 0 1 2";

    //$input = file_get_contents("php://stdin");

    return $input;
}

function convertInputToMatrix(string $input): array
{
    $rows = explode(PHP_EOL, $input);

    $matrix = [];

    for ($i = 1; $i < count($rows); $i++) {
        $columns = explode(' ', $rows[$i]);

        for ($j = 0; $j < count($columns); $j++) {
            $matrix[$i-1][$j] = $columns[$j];
        }
    }

    return $matrix;
}

function getWaterSizes(array $matrix): array
{
    $visited = [];

    $rowCount = count($matrix);

    $waterSizes = [];

    for ($i = 0; $i < $rowCount; $i++) {
        $colCount = count($matrix[$i]);

        for ($j = 0; $j < $colCount; $j++) {
            if ($matrix[$i][$j] > 0) {
                $visited[$i][$j] = 1;
            }
            elseif (!isset($visited[$i][$j])) {
                $waterSizes[] = getMaxHops($i, $j, $matrix, $visited);
            }
        }
    }

    return $waterSizes;
}

function getMaxHops($i, $j, $matrix, &$visited)
{
    $visited[$i][$j]   = 1;

    $neighboursHops = 0;

    $neighbours = getUnvisitedNeighboursUnderWater($i, $j, $matrix, $visited);

    foreach ($neighbours as $neighbour)
    {
        $k = $neighbour['i'];
        $l = $neighbour['j'];

        $visited[$k][$l] = 1;

        if ($matrix[$k][$l] == 0)
        {
            $neighboursHops += getMaxHops($k, $l, $matrix, $visited);
        }
    }

    return $neighboursHops + 1;
}

function getUnvisitedNeighboursUnderWater($i, $j, $matrix, &$visited)
{
    $neighbours = [];

    $possibilities   = [];
    $possibilities[] = [$i-1, $j-1];
    $possibilities[] = [$i-1, $j];
    $possibilities[] = [$i-1, $j+1];
    $possibilities[] = [$i, $j-1];
    $possibilities[] = [$i, $j+1];
    $possibilities[] = [$i+1, $j-1];
    $possibilities[] = [$i+1, $j];
    $possibilities[] = [$i+1, $j+1];

    foreach ($possibilities as $possibility)
    {
        list($i, $j) = $possibility;

        if (
            isset($matrix[$i][$j])
            && $matrix[$i][$j] == 0
            && !isset($visited[$i][$j])
        ) {
            $neighbours[] = ['i' => $i, 'j' => $j];
        }
    }

    return $neighbours;
}

function getUniqueAndSortedWaterSizes(array $sizes): array
{
    $uniqueWaterSizes = array_unique($sizes);

    asort($uniqueWaterSizes);

    return $uniqueWaterSizes;
}

function renderResult(array $result)
{
    echo implode(PHP_EOL, $result);
}

$input = getInput();

$matrix = convertInputToMatrix($input);

$waterSizes = getWaterSizes($matrix);

$uniqueAndSortedWaterSizes = getUniqueAndSortedWaterSizes($waterSizes);

renderResult($uniqueAndSortedWaterSizes);
