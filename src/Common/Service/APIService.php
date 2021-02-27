<?php

namespace future\Common\Service;
use future\Controller\ParseController;

/**
 * A generic class to handle all CSV read requests
 *
 * Class APIService
 * @package future\Common\Service
 */
abstract class APIService
{
    /**
     * APIService constructor.
     */
    public function __construct(){}

    /**
     * Reads contents from the CSV file then return into array
     *
     * @param $dir
     * @return array
     */
    public function getContents($dir) : array
    {
        $dirArray  = scandir($dir);
        $dataArray = array();

        foreach ($dirArray as $directory) {
            /** If filename is integer process files **/
            if (is_numeric($directory)) {

                if ($handleDir = opendir($dir.'/'.$directory)) {
                    /** loop over the directory **/
                    while (false !== ($entry = readdir($handleDir))) {
                        /** Read CSV **/
                        $rows         = array_map('str_getcsv', file($dir.'/'.$directory.'/'.$entry));
                        $header       = array_shift($rows);

                        foreach($rows as $row) {
                            $dataArray[str_replace('log', 'csv', $entry)][] = array_combine($header, $row);
                        }
                    }

                    closedir($handleDir);
                }
            }
        }

        return $dataArray;
    }
}