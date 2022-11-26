<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\test\Utils;

use App\Utils\UUID;
use PHPUnit\Framework\TestCase;

class UUIDTest extends TestCase {
    
    public function testIsValid()
    {
        //$uuid = new UUID();
        $isValid = UUID::is_valid('1546058f-5a25-4334-85ae-e68f2a44bbaf');
        $this->assertEquals(true, $isValid);
    }
    
    public function testUUIDv4()
    {
        //$uuid = new UUID();
        $v51 = UUID::v5('onepolis',"0gg1s1f3st3gg14");
        $v52 = UUID::v5('onepolis',"0gg1s1f3st3gg14");
        $this->assertEquals($v51, $v52);
        
        $v4a = UUID::v4();
        $v4b = UUID::v4();
        $this->assertNotEquals($v4a, $v4b);
    }
    
}