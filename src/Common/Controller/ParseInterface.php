<?php
namespace future\Common\Controller;

/**
 * Declaring the methods needed to generate the repaired CSVs
 *
 * Interface ParseInterface
 * @package Common\Controller
 */
interface ParseInterface
{
    /**
     * Generates the CSV
     *
     * @param $directory
     * @return mixed
     */
    public function generateCSV($directory);
}