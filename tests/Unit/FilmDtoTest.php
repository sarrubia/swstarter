<?php

use App\Http\Dtos\FilmDto;

describe('FilmDto::make', function () {

    it('creates a FilmDto with all properties', function () {
        $dto = FilmDto::make(
            '1',
            '4',
            'Gary Kurtz',
            'A New Hope',
            'George Lucas',
            '1977-05-25',
            'It is a period of civil war...',
            ['/api/people/1']
        );

        expect($dto)->toBeInstanceOf(FilmDto::class)
            ->and($dto->getUid())->toBe('1')
            ->and($dto->getEpisodeId())->toBe('4')
            ->and($dto->getProducer())->toBe('Gary Kurtz')
            ->and($dto->getTitle())->toBe('A New Hope')
            ->and($dto->getDirector())->toBe('George Lucas')
            ->and($dto->getReleaseDate())->toBe('1977-05-25')
            ->and($dto->getOpeningCrawl())->toBe('It is a period of civil war...');
    });
});

describe('FilmDto::fromArray', function () {

    it('creates FilmDto from array with all fields', function () {
        $data = [
            'uid' => '1',
            'episode_id' => '4',
            'producer' => 'Gary Kurtz',
            'title' => 'A New Hope',
            'director' => 'George Lucas',
            'release_date' => '1977-05-25',
            'opening_crawl' => 'It is a period of civil war...',
            'characters' => ['/api/people/1', '/api/people/2']
        ];

        $dto = FilmDto::fromArray($data);

        expect($dto->getUid())->toBe('1')
            ->and($dto->getEpisodeId())->toBe('4')
            ->and($dto->getProducer())->toBe('Gary Kurtz')
            ->and($dto->getTitle())->toBe('A New Hope')
            ->and($dto->getDirector())->toBe('George Lucas')
            ->and($dto->getReleaseDate())->toBe('1977-05-25')
            ->and($dto->getOpeningCrawl())->toBe('It is a period of civil war...');
    });

    it('uses "unknown" as default for missing fields', function () {
        $data = [
            'uid' => '1',
            'title' => 'A New Hope'
        ];

        $dto = FilmDto::fromArray($data);

        expect($dto->getUid())->toBe('1')
            ->and($dto->getTitle())->toBe('A New Hope')
            ->and($dto->getEpisodeId())->toBe('unknown')
            ->and($dto->getProducer())->toBe('unknown')
            ->and($dto->getDirector())->toBe('unknown')
            ->and($dto->getReleaseDate())->toBe('unknown')
            ->and($dto->getOpeningCrawl())->toBe('unknown');
    });

    it('creates empty FilmDto from empty array', function () {
        $dto = FilmDto::fromArray([]);

        expect($dto)->toBeInstanceOf(FilmDto::class);
    });
});

describe('FilmDto::toArray', function () {

    it('converts FilmDto to array with correct structure', function () {
        $dto = FilmDto::make(
            '1',
            '4',
            'Gary Kurtz',
            'A New Hope',
            'George Lucas',
            '1977-05-25',
            'It is a period of civil war...',
            ['/api/people/1']
        );

        $array = $dto->toArray();

        expect($array)->toBe([
            'uid' => '1',
            'producer' => 'Gary Kurtz',
            'title' => 'A New Hope',
            'director' => 'George Lucas',
            'release_date' => '1977-05-25',
            'opening_crawl' => 'It is a period of civil war...',
            'episode_id' => '4',
            'characters' => ['/api/people/1']
        ]);
    });

    it('round trips from array to DTO and back', function () {
        $original = [
            'uid' => '2',
            'episode_id' => '5',
            'producer' => 'Gary Kurtz',
            'title' => 'The Empire Strikes Back',
            'director' => 'Irvin Kershner',
            'release_date' => '1980-05-17',
            'opening_crawl' => 'It is a dark time...',
            'characters' => ['/api/people/1', '/api/people/2']
        ];

        $dto = FilmDto::fromArray($original);
        $result = $dto->toArray();

        expect($result)->toEqual($original);
    });
});

describe('FilmDto getters', function () {

    beforeEach(function () {
        $this->dto = FilmDto::make(
            '1',
            '4',
            'Gary Kurtz, Rick McCallum',
            'A New Hope',
            'George Lucas',
            '1977-05-25',
            'It is a period of civil war. Rebel spaceships...',
            ['/api/people/1', '/api/people/2']
        );
    });

    it('getUid returns correct value', function () {
        expect($this->dto->getUid())->toBe('1');
    });

    it('getEpisodeId returns correct value', function () {
        expect($this->dto->getEpisodeId())->toBe('4');
    });

    it('getProducer returns correct value', function () {
        expect($this->dto->getProducer())->toBe('Gary Kurtz, Rick McCallum');
    });

    it('getTitle returns correct value', function () {
        expect($this->dto->getTitle())->toBe('A New Hope');
    });

    it('getDirector returns correct value', function () {
        expect($this->dto->getDirector())->toBe('George Lucas');
    });

    it('getReleaseDate returns correct value', function () {
        expect($this->dto->getReleaseDate())->toBe('1977-05-25');
    });

    it('getOpeningCrawl returns correct value', function () {
        expect($this->dto->getOpeningCrawl())->toBe('It is a period of civil war. Rebel spaceships...');
    });
});
