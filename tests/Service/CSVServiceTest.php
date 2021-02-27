<?php

use future\Common\Service\APIService;
use future\Service\CSVService;
use PHPUnit\Framework\TestCase;

class CSVServiceTest extends TestCase
{
    private $csvColumnArray = array(
        'test.csv' => array(
            'id'                                 => 0,
            'appCode'                            => 'admin-magazine',
            'deviceId'                           => 'FD80B21E811F44F56657EEBDB4671CB27DE33CF175E05806B0437FC20CC88A0F',
            'contactable'                        => '1',
            'subscription_status'                => 'never_subscribed',
            'has_downloaded_free_product_status' => '',
            'has_downloaded_iap_product_status'  => ''
        )
    );

    public function testGetRawCSVContent(): void
    {
        $mock = $this->createMock(CSVService::class);
        $mock->method('getRawCSVContent')
            ->willReturn($this->csvColumnArray);
        $this->assertEquals($this->csvColumnArray, $mock->getRawCSVContent('/parser_test'));

        $mockedClass = $this->createMock(APIService::class);
        $mockedClass->method('getContents')
            ->willReturn($this->csvColumnArray);

        $this->assertEquals($this->csvColumnArray, $mockedClass->getContents('/parser_test'));
    }
}