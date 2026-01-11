<?php

namespace TaiwanIdValidator;

class TaiwanIdValidator
{
    private static $letterWeights = [
        'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16,
        'H' => 17, 'J' => 18, 'K' => 19, 'L' => 20, 'M' => 21, 'N' => 22, 'P' => 23,
        'Q' => 24, 'R' => 25, 'S' => 26, 'T' => 27, 'U' => 28, 'V' => 29, 'X' => 30,
        'Y' => 31, 'W' => 32, 'Z' => 33, 'I' => 34, 'O' => 35,
    ];

    private static $ubnWeights = [1, 2, 1, 2, 1, 2, 4, 1];

    /**
     * Validate checks if the given ID is a valid Taiwan National ID or Alien Resident Certificate number.
     *
     * It supports:
     * 1. National ID (ex: A123456789)
     * 2. New Alien Resident Certificate (ex: A800000014)
     * 3. Old Alien Resident Certificate (ex: AC01234567)
     *
     * @param string $id
     * @return bool
     */
    public static function validate(string $id): bool
    {
        $id = strtoupper($id);

        // National ID or New ARC
        if (preg_match('/^[A-Z][12]\d{8}$/', $id) || preg_match('/^[A-Z][89]\d{8}$/', $id)) {
            return self::validateModernIdCardFormat($id);
        }

        // Old ARC
        if (preg_match('/^[A-Z][A-Z]\d{8}$/', $id)) {
            return self::validateOldArcIdFormat($id);
        }

        return false;
    }

    /**
     * ValidateNationId checks if the given ID is a valid Taiwan National ID.
     *
     * @param string $id
     * @return bool
     */
    public static function validateNationId(string $id): bool
    {
        $id = strtoupper($id);
        if (preg_match('/^[A-Z][12]\d{8}$/', $id)) {
            return self::validateModernIdCardFormat($id);
        }
        return false;
    }

    /**
     * ValidateArcId checks if the given ID is a valid Taiwan Alien Resident Certificate number.
     *
     * @param string $id
     * @return bool
     */
    public static function validateArcId(string $id): bool
    {
        $id = strtoupper($id);
        if (preg_match('/^[A-Z][89]\d{8}$/', $id)) {
            return self::validateModernIdCardFormat($id);
        }
        if (preg_match('/^[A-Z][A-Z]\d{8}$/', $id)) {
            return self::validateOldArcIdFormat($id);
        }
        return false;
    }

    /**
     * ValidateUbn checks if the given UBN is a valid Taiwan Company Tax ID.
     *
     * @param string $ubn
     * @return bool
     */
    public static function validateUbn(string $ubn): bool
    {
        if (!preg_match('/^\d{8}$/', $ubn)) {
            return false;
        }

        if ($ubn === "00000000") {
            return false;
        }

        $sum1 = 0;
        $sum2 = 0;

        for ($i = 0; $i < 8; $i++) {
            $weight = self::$ubnWeights[$i];
            
            if ($i == 6 && $ubn[$i] == '7') {
                $sum1 += 1;
                $sum2 += 0;
            } else {
                $digit = intval($ubn[$i]);
                $product = $digit * $weight;
                $pSum = intval($product / 10) + ($product % 10);
                $sum1 += $pSum;
                $sum2 += $pSum;
            }
        }

        if ($sum1 % 5 == 0 || $sum2 % 5 == 0) {
            return true;
        }

        return false;
    }

    private static function validateModernIdCardFormat(string $id): bool
    {
        $sum = 0;
        $firstChar = $id[0];
        
        if (!isset(self::$letterWeights[$firstChar])) {
            return false;
        }
        
        $areaWeight = self::$letterWeights[$firstChar];
        $sum += intval($areaWeight / 10) * 1 + ($areaWeight % 10) * 9;
        
        $weights = [8, 7, 6, 5, 4, 3, 2, 1];
        
        for ($i = 0; $i < 8; $i++) {
            $char = $id[$i + 1];
            $num = intval($char);
            $sum += $num * $weights[$i];
        }
        
        $checkDigit = intval($id[9]);
        $sum += $checkDigit;
        
        return $sum % 10 == 0;
    }

    private static function validateOldArcIdFormat(string $id): bool
    {
        $sum = 0;
        $firstChar = $id[0];
        
        if (!isset(self::$letterWeights[$firstChar])) {
            return false;
        }
        
        $areaWeight = self::$letterWeights[$firstChar];
        $sum += intval($areaWeight / 10) * 1 + ($areaWeight % 10) * 9;
        
        $secondChar = $id[1];
        if (!isset(self::$letterWeights[$secondChar])) {
            return false;
        }
        
        $genderWeight = self::$letterWeights[$secondChar];
        $sum += ($genderWeight % 10) * 8;
        
        $weights = [7, 6, 5, 4, 3, 2, 1];
        
        for ($i = 0; $i < 7; $i++) {
            $char = $id[$i + 2];
            $num = intval($char);
            $sum += $num * $weights[$i];
        }
        
        $checkDigit = intval($id[9]);
        $sum += $checkDigit;
        
        return $sum % 10 == 0;
    }
}
