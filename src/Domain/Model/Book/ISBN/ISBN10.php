<?php

namespace RJozwiak\Libroteca\Domain\Model\Book\ISBN;

class ISBN10 extends ISBN
{
    private const FORMAT = '/^\d{9}[0-9X]$/';

    /**
     * @return string
     */
    protected function format()
    {
        return self::FORMAT;
    }

    /**
     * @param string $isbn
     * @return string
     */
    protected function checksumDigit($isbn)
    {
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (10 - $i) * $isbn[$i];
        }
        $controlNumber = 11 - ($sum % 11);

        return $controlNumber == 10 ? 'X' : (string) $controlNumber;
    }
}
