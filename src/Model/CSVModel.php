<?php

namespace future\Model;

use Exception;
use future\Common\Model\CSVModelInterface;
use future\Service\CSVService;

/**
 * Class CSVModel
 * @package future\Model
 */
class CSVModel implements CSVModelInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $app;

    /**
     * @var string
     */
    private $device;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var CSVService
     */
    private $csvService;

    /**
     * subscriptonStatusArray
     *
     * @var string[]
     */
    private $subscriptonStatusArray = array(
        'active_subscriber',
        'expired_subscriber',
        'never_subscribed',
        'subscription_unknown'
    );

    /**
     * downloadedFreeStatusArray
     *
     * @var string[]
     */
    private $downloadedFreeStatusArray = array(
        'has_downloaded_free_product',
        'not_downloaded_free_product',
        'downloaded_free_product_unknown'
    );

    /**
     * downloadedIapStatusArray
     *
     * @var string[]
     */
    private $downloadedIapStatusArray = array(
        'has_downloaded_iap_product',
        'not_downloaded_iap_product',
        'downloaded_iap_product_unknown'
    );

    /**
     * @var string
     */
    private $subscriptionStatus;

    /**
     * @var string
     */
    private $downloadedFreeStatus;

    /**
     * @var string
     */
    private $downloadedIapStatus;

    /**
     * CSVModel constructor.
     */
    public function __construct()
    {
        $this->directory            = '';
        $this->app                  = '';
        $this->device               = '';
        $this->status               = '';
        $this->subscriptionStatus   = '';
        $this->downloadedFreeStatus = '';
        $this->downloadedIapStatus  = '';
        $this->csvService           = new CSVService();
    }

    /**
     * Set the directory variable type
     *
     * @param $directory
     * @return string
     * @throws Exception
     */
    public function setDirectory($directory): string
    {
        if (!is_dir(__DIR__.$directory)) {
            throw new Exception('Directory does not exist');
        }

        return $this->directory = __DIR__.$directory;
    }

    /**
     * Set the app variable type
     *
     * @param $app
     * @return string
     * @throws Exception
     */
    public function setApp($app): string
    {
        $appCodesArray = parse_ini_file($this->directory.'/appCodes.ini', true);
        if (in_array($app, $appCodesArray['appcodes']) == false) {
            throw new Exception('App code does not exist');
        }

        return $this->app = array_search($app, $appCodesArray['appcodes']);
    }

    /**
     * Set the device variable type
     *
     * @param $device
     * @return string
     * @throws Exception
     */
    public function setDevice($device): string
    {
        if (!is_string($device)) {
            throw new Exception('Device needs to be a string');
        }

        return $this->device = (string)$device;
    }

    /**
     * Set the device token status variable type
     *
     * @param $status
     * @return string
     * @throws Exception
     */
    public function setDeviceTokenStatus($status): string
    {
        if ((int)$status > 1) {
            throw new Exception('Device token status needs to be a null or 1');
        }

        if (empty($status)) {
            $status = '0';
        }

        return $this->status = $status;
    }

    /**
     * Set the tags variable type
     *
     * @param $tag
     * @return bool
     * @throws Exception
     */
    public function setTags($tag): bool
    {
        $tagReturn = true;

        if (!empty($tag)) {
            $file = fopen('error_tag.log',"a+") or exit ("Unable to open file!");
            $tagExplodedArray = explode('|', $tag);
            foreach ($tagExplodedArray as $tagExploded) {
                if (in_array($tagExploded, $this->subscriptonStatusArray)) {
                    $this->subscriptionStatus = $tagExploded;
                } else if (in_array($tagExploded, $this->downloadedFreeStatusArray)) {
                    $this->downloadedFreeStatus = $tagExploded;
                } else if (in_array($tagExploded, $this->downloadedIapStatusArray)) {
                    $this->downloadedIapStatus = $tagExploded;
                } else {
                    fwrite($file,"Following tag does not exist: ".$tagExploded."\n");
                    $tagReturn = false;
                }
            }

            fclose($file);
        }

        return $tagReturn;
    }

    /**
     * Retrieve directory value
     *
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Retrieve app value
     *
     * @return string
     */
    public function getApp(): string
    {
        return $this->app;
    }

    /**
     * Retrieve device value
     *
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * Retrieve device token status value
     *
     * @return bool
     */
    public function getDeviceTokenStatus(): bool
    {
        return $this->status;
    }

    /**
     * Retrieve subscription status value
     *
     * @return string
     */
    public function getSubscriptionStatus(): string
    {
        return $this->subscriptionStatus;
    }

    /**
     * Retrieve downloaded free status value
     *
     * @return string
     */
    public function getDownloadedFreeStatus(): string
    {
        return $this->downloadedFreeStatus;
    }

    /**
     * Retrieve downloaded iap status value
     *
     * @return string
     */
    public function getDownloadedIapStatus(): string
    {
        return $this->downloadedIapStatus;
    }

    /**
     * Fetch un-formatted CSV
     *
     * @param $directory
     * @return array
     * @throws Exception
     */
    public function fetchUnformattedCSV($directory): array
    {
        if (!empty($directory)) {
            $this->setDirectory($directory);
        }

        $dir = $this->getDirectory();

        return $this->getRawCSV($dir);
    }

    /**
     * Gets raw CSV data
     *
     * @param $dir
     * @return mixed|string|true
     */
    public function getRawCSV($dir) : array
    {
        return $this->csvService->getRawCSVContent($dir);
    }

    /**
     * Prepares the data to be displayed in new CSV
     *
     * @param $unformattedData
     * @return bool
     */
    public function generateFormattedCSV($unformattedData) : bool
    {
        $csvColumnArray= array();
        if (!empty($unformattedData)) {
            try {
                foreach ($unformattedData as $fileName => $dataSet) {
                    foreach ($dataSet as $key => $item) {
                        /** Set up CSV data **/
                        $this->setApp($item['app']);
                        $this->setDevice($item['deviceToken']);
                        $this->setDeviceTokenStatus($item['deviceTokenStatus']);
                        $this->setTags($item['tags']);

                        /** Prepare data for new CSV **/
                        $csvColumnArray[$fileName][$key]['id']                                 = $key;
                        $csvColumnArray[$fileName][$key]['appCode']                            = $this->getApp();
                        $csvColumnArray[$fileName][$key]['deviceId']                           = $this->getDevice();
                        $csvColumnArray[$fileName][$key]['contactable']                        = empty($this->getDeviceTokenStatus()) ? '0' : $this->getDeviceTokenStatus();
                        $csvColumnArray[$fileName][$key]['subscription_status']                = $this->getSubscriptionStatus();
                        $csvColumnArray[$fileName][$key]['has_downloaded_free_product_status'] = $this->getDownloadedFreeStatus();
                        $csvColumnArray[$fileName][$key]['has_downloaded_iap_product_status']  = $this->getDownloadedIapStatus();
                    }
                }
            } catch (Exception $e) {
                print($e->getMessage());
            }

            $this->createCSV($csvColumnArray);
        }

        return true;
    }

    /**
     * Creates the new CSV
     *
     * @param $csvColumnArray
     * @return bool
     */
    private function createCSV($csvColumnArray) : bool
    {
        foreach ($csvColumnArray as $filename => $data) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.$filename);

            $headers = array(
                'id',
                'appCode',
                'deviceId',
                'contactable',
                'subscription_status',
                'has_downloaded_free_product_status',
                'has_downloaded_iap_product_status'
            );

            $file = fopen($filename, "w");

            fputcsv($file, $headers);
            foreach ($data as $content) {
                fputcsv($file, $content);
            }

            fclose($file);
        }

        return true;
    }
}