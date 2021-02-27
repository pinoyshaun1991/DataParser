<?php

namespace future\Controller;

use future\Common\Controller\ParseInterface;
use Exception;
use future\Model\CSVModel;

/**
 * Implements the parse interface
 *
 * Class ParseController
 * @package future\Controller
 */
class ParseController implements ParseInterface
{
    private $csvModel;

    /**
     * ParseController constructor.
     */
    public function __construct()
    {
        $this->csvModel = new CSVModel();
    }

    /**
     * Generates the CSV
     *
     * @param $directory
     * @return bool
     */
    public function generateCSV($directory)
    {
        try {
            $unformattedData = $this->csvModel->fetchUnformattedCSV($directory);
            $this->csvModel->generateFormattedCSV($unformattedData);
        } catch (Exception $e) {
            print $e->getMessage();
        }

        return true;
    }
}