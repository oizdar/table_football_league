<?php
namespace TableFootball\League\Helpers;

use TableFootball\League\Exceptions\InvalidArgumentException;

class ValidationHelper
{
    public static function checkRequiredFields(array $keysToCheck, array $dataArray)
    {
        foreach($keysToCheck as $key) {
            if(!isset($dataArray[$key])) {
                throw new InvalidArgumentException("Field: \"{$key}\" is required and should not be empty.");
            }
        }
    }

    public static function checkPlayerNames(array $players)
    {
        if(count($players) < 4) {
            throw new InvalidArgumentException('Have to be minimum 4 players.');
        }
        if($players !== array_unique($players)) {
            throw new InvalidArgumentException('Player names must be unique.');
        }
        foreach($players as $player) {
            static::checkStringLength(100, $player, 'player');
        }
    }

    public static function checkStringLength(int $length, string $string, string $fieldName)
    {
        if(strlen($string) > $length) {
            throw new InvalidArgumentException("Field: \"$fieldName\" maximum length reached. Max characters number is: $length");
        }
    }

    public static function checkScoreFormat(string $score)
    {
        if(!preg_match('/^\d+(-|:)\d+$/', $score)) {
            throw new InvalidArgumentException('Invalid Score format');
        }
    }
}
