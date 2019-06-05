<?php

use PHPUnit\Framework\TestCase;

class FakeTest extends TestCase
{
    public function testNothing()
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
    }
}
