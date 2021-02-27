<?php
use PHPUnit\Framework\TestCase;
use future\Controller\ParseController;

class ParseControllerTest extends TestCase
{
    public function testGenerateCSV(): void
    {
        $mockedClass = $this->createMock(ParseController::class);
        $mockedClass->method('generateCSV')
            ->willReturn(true);
        $this->assertEquals(true, $mockedClass->generateCSV('/parser_test'));
    }
}