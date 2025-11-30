<?php

namespace App\Http\Dtos;

use App\Lib\Utils\ArrayUtils;

/**
 * PersonDto immutable class representing a Person from StarWars API.
 */
class PersonDto extends AbstractDto
{
    public const FIELD_UID = 'uid';
    public const FIELD_NAME = 'name';
    public const FIELD_GENDER = 'gender';
    public const FIELD_SKIN_COLOR = 'skin_color';
    public const FIELD_HAIR_COLOR = 'hair_color';
    public const FIELD_HEIGHT = 'height';
    public const FIELD_EYE_COLOR = 'eye_color';
    public const FIELD_MASS = 'mass';
    public const FIELD_BIRTH_YEAR = 'birth_year';

    /**
     * @var string
     */
    private string $uid;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $gender;

    /**
     * @var string
     */
    private string $skinColor;

    /**
     * @var string
     */
    private string $hairColor;

    /**
     * @var string
     */
    private string $height;

    /**
     * @var string
     */
    private string $eyeColor;

    /**
     * @var string
     */
    private string $mass;

    /**
     * @var string
     */
    private string $birthYear;

    private function __construct() {
        /*
            force developers to build object instance from its builder methods:
                - make
                - fromArray
        */
    }

    /**
     * Builder static method used as object factory
     * @param $uid
     * @param $name
     * @param $gender
     * @param $skinColor
     * @param $hairColor
     * @param $height
     * @param $eyeColor
     * @param $mass
     * @param $birthYear
     * @return PersonDto
     */
    public static function make($uid, $name, $gender, $skinColor, $hairColor, $height, $eyeColor, $mass, $birthYear): PersonDto {
        $instance = new self();

        $instance->uid = $uid;
        $instance->name = $name;
        $instance->gender = $gender;
        $instance->skinColor = $skinColor;
        $instance->hairColor = $hairColor;
        $instance->height = $height;
        $instance->eyeColor = $eyeColor;
        $instance->mass = $mass;
        $instance->birthYear = $birthYear;

        return $instance;
    }

    /**
     * Builder static method used as object factory from a given array
     * @param array $data
     * @return PersonDto
     */
    public static function fromArray(array $data): PersonDto {

        if (empty($data)) {
            return new self();
        }

        $uid = ArrayUtils::keyExists(self::FIELD_UID, $data, self::UNKNOWN );
        $name = ArrayUtils::keyExists(self::FIELD_NAME, $data, self::UNKNOWN );
        $gender = ArrayUtils::keyExists(self::FIELD_GENDER, $data, self::UNKNOWN );
        $skinColor = ArrayUtils::keyExists(self::FIELD_SKIN_COLOR, $data, self::UNKNOWN );
        $hairColor = ArrayUtils::keyExists(self::FIELD_HAIR_COLOR, $data, self::UNKNOWN );
        $height = ArrayUtils::keyExists(self::FIELD_HEIGHT, $data, self::UNKNOWN );
        $eyeColor = ArrayUtils::keyExists(self::FIELD_EYE_COLOR, $data, self::UNKNOWN );
        $mass = ArrayUtils::keyExists(self::FIELD_MASS, $data, self::UNKNOWN );
        $birthYear = ArrayUtils::keyExists(self::FIELD_BIRTH_YEAR, $data, self::UNKNOWN );

        return self::make(
            $uid,
            $name,
            $gender,
            $skinColor,
            $hairColor,
            $height,
            $eyeColor,
            $mass,
            $birthYear
        );
    }

    /**
     * returns a array representation of the object
     * @return array the array representation
     */
    public function toArray(): array
    {
        return [
            self::FIELD_UID => $this->uid,
            self::FIELD_NAME => $this->name,
            self::FIELD_GENDER => $this->gender,
            self::FIELD_SKIN_COLOR => $this->skinColor,
            self::FIELD_HAIR_COLOR => $this->hairColor,
            self::FIELD_HEIGHT => $this->height,
            self::FIELD_EYE_COLOR => $this->eyeColor,
            self::FIELD_MASS => $this->mass,
            self::FIELD_BIRTH_YEAR => $this->birthYear
        ];
    }

    /**
     * @return string the person uid
     */
    public function getUid(): string {
        return $this->uid;
    }

    /**
     * @return string the person name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string the person gender
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * @return string the person skin color
     */
    public function getSkinColor(): string
    {
        return $this->skinColor;
    }

    /**
     * @return string the person hair color
     */
    public function getHairColor(): string
    {
        return $this->hairColor;
    }

    /**
     * @return string the person height
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @return string the person eye color
     */
    public function getEyeColor(): string
    {
        return $this->eyeColor;
    }

    /**
     * @return string the person mass
     */
    public function getMass(): string
    {
        return $this->mass;
    }

    /**
     * @return string the person birth year
     */
    public function getBirthYear(): string
    {
        return $this->birthYear;
    }
}
