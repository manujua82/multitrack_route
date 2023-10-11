<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Address;

class AdressTest extends TestCase
{
    public function testGetAndSetData(): void
    {
        $address = new Address();
        $address->setCode('CCS');
        $address->setStreet('AV SAN MARTIN');
        $address->setCity('CARACAS');
        $address->setState('DISTRITO CAPITAL');
        $address->setCountry('VENEZUELA');
        $address->setFullAddress('Edif Boyaca, Piso 9');
        $address->setLatitude('10.4971016');
        $address->setLongitude('-66.9265834');
        $address->setPostalcode(1030);

        self::assertSame("CCS", $address->getCode());
        self::assertSame("AV SAN MARTIN", $address->getStreet());
        self::assertSame("CARACAS", $address->getCity());
        self::assertSame("DISTRITO CAPITAL", $address->getState());
        self::assertSame("VENEZUELA", $address->getCountry());
        self::assertSame("Edif Boyaca, Piso 9", $address->getFullAddress());
        self::assertSame("10.4971016", $address->getLatitude());
        self::assertSame("-66.9265834", $address->getLongitude());
        self::assertSame(1030, $address->getPostalcode());
    }
}
