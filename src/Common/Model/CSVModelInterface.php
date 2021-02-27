<?php
namespace future\Common\Model;

/**
 * Declaring the methods needed to generate the CSV
 *
 * Interface CSVModelInterface
 * @package future\Common\Model
 */
interface CSVModelInterface
{
    /**
     * Prepares the unformatted CSV
     *
     * @param $directory
     * @return mixed
     */
    public function fetchUnformattedCSV($directory);

    /**
     * Gets the raw CSV
     *
     * @param $dir
     * @return mixed
     */
    public function getRawCSV($dir);
}