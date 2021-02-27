<?php
 namespace future\Service;

 use future\Common\Service\APIService;

 /**
  * Service returning specifically raw CSV content
  *
  * Class CSVService
  * @package future\Service
  */
class CSVService extends APIService
{
    /**
     * Get the contents of the CSV read response
     *
     * @param $dir
     * @return array
     */
    public function getRawCSVContent($dir) : array
    {
        return $this->getContents($dir);
    }
}