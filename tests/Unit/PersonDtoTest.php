<?php

use App\Http\Dtos\PersonDto;

describe('PersonDto::make', function () {

    it('creates a PersonDto with all properties', function () {
        $dto = PersonDto::make(
            '1',
            'Luke Skywalker',
            'male',
            'fair',
            'blond',
            '172',
            'blue',
            '77',
            '19BBY',
            ['/api/films/1']
        );

        expect($dto)->toBeInstanceOf(PersonDto::class)
            ->and($dto->getUid())->toBe('1')
            ->and($dto->getName())->toBe('Luke Skywalker')
            ->and($dto->getGender())->toBe('male')
            ->and($dto->getSkinColor())->toBe('fair')
            ->and($dto->getHairColor())->toBe('blond')
            ->and($dto->getHeight())->toBe('172')
            ->and($dto->getEyeColor())->toBe('blue')
            ->and($dto->getMass())->toBe('77')
            ->and($dto->getBirthYear())->toBe('19BBY')
            ->and($dto->getFilms())->toBe(['/api/films/1']);
    });
});

describe('PersonDto::fromArray', function () {

    it('creates PersonDto from array with all fields', function () {
        $data = [
            'uid' => '1',
            'name' => 'Luke Skywalker',
            'gender' => 'male',
            'skin_color' => 'fair',
            'hair_color' => 'blond',
            'height' => '172',
            'eye_color' => 'blue',
            'mass' => '77',
            'birth_year' => '19BBY',
            'films' => ['/api/films/1', '/api/films/2']
        ];

        $dto = PersonDto::fromArray($data);

        expect($dto->getUid())->toBe('1')
            ->and($dto->getName())->toBe('Luke Skywalker')
            ->and($dto->getGender())->toBe('male')
            ->and($dto->getSkinColor())->toBe('fair')
            ->and($dto->getHairColor())->toBe('blond')
            ->and($dto->getHeight())->toBe('172')
            ->and($dto->getEyeColor())->toBe('blue')
            ->and($dto->getMass())->toBe('77')
            ->and($dto->getBirthYear())->toBe('19BBY')
            ->and($dto->getFilms())->toBe(['/api/films/1', '/api/films/2']);
    });

    it('uses "unknown" as default for missing fields', function () {
        $data = [
            'uid' => '1',
            'name' => 'Luke Skywalker'
        ];

        $dto = PersonDto::fromArray($data);

        expect($dto->getUid())->toBe('1')
            ->and($dto->getName())->toBe('Luke Skywalker')
            ->and($dto->getGender())->toBe('unknown')
            ->and($dto->getSkinColor())->toBe('unknown')
            ->and($dto->getHairColor())->toBe('unknown')
            ->and($dto->getHeight())->toBe('unknown')
            ->and($dto->getEyeColor())->toBe('unknown')
            ->and($dto->getMass())->toBe('unknown')
            ->and($dto->getBirthYear())->toBe('unknown')
            ->and($dto->getFilms())->toBe([]);
    });

    it('creates empty PersonDto from empty array', function () {
        $dto = PersonDto::fromArray([]);

        expect($dto)->toBeInstanceOf(PersonDto::class);
    });
});

describe('PersonDto::toArray', function () {

    it('converts PersonDto to array with correct structure', function () {
        $dto = PersonDto::make(
            '1',
            'Luke Skywalker',
            'male',
            'fair',
            'blond',
            '172',
            'blue',
            '77',
            '19BBY',
            ['/api/films/1']
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'uid' => '1',
            'name' => 'Luke Skywalker',
            'gender' => 'male',
            'skin_color' => 'fair',
            'hair_color' => 'blond',
            'height' => '172',
            'eye_color' => 'blue',
            'mass' => '77',
            'birth_year' => '19BBY',
            'films' => ['/api/films/1']
        ]);
    });

    it('round trips from array to DTO and back', function () {
        $original = [
            'uid' => '2',
            'name' => 'C-3PO',
            'gender' => 'n/a',
            'skin_color' => 'gold',
            'hair_color' => 'n/a',
            'height' => '167',
            'eye_color' => 'yellow',
            'mass' => '75',
            'birth_year' => '112BBY',
            'films' => []
        ];

        $dto = PersonDto::fromArray($original);
        $result = $dto->toArray();

        expect($result)->toBe($original);
    });
});

describe('PersonDto getters', function () {

    beforeEach(function () {
        $this->dto = PersonDto::make(
            '1',
            'Luke Skywalker',
            'male',
            'fair',
            'blond',
            '172',
            'blue',
            '77',
            '19BBY',
            ['/api/films/1', '/api/films/2']
        );
    });

    it('getUid returns correct value', function () {
        expect($this->dto->getUid())->toBe('1');
    });

    it('getName returns correct value', function () {
        expect($this->dto->getName())->toBe('Luke Skywalker');
    });

    it('getGender returns correct value', function () {
        expect($this->dto->getGender())->toBe('male');
    });

    it('getSkinColor returns correct value', function () {
        expect($this->dto->getSkinColor())->toBe('fair');
    });

    it('getHairColor returns correct value', function () {
        expect($this->dto->getHairColor())->toBe('blond');
    });

    it('getHeight returns correct value', function () {
        expect($this->dto->getHeight())->toBe('172');
    });

    it('getEyeColor returns correct value', function () {
        expect($this->dto->getEyeColor())->toBe('blue');
    });

    it('getMass returns correct value', function () {
        expect($this->dto->getMass())->toBe('77');
    });

    it('getBirthYear returns correct value', function () {
        expect($this->dto->getBirthYear())->toBe('19BBY');
    });

    it('getFilms returns correct array', function () {
        expect($this->dto->getFilms())->toBe(['/api/films/1', '/api/films/2']);
    });
});
