<?php

namespace App\Http\Dtos;

use App\Lib\Utils\ArrayUtils;

/**
 *  FilmsDto Data Transfer Object to send Film data from backend to frontend or other service/module
 *  This class is immutable and readonly once that has been build.
 */
class FilmDto extends AbstractDto
{
    public const FIELD_UID = 'uid';
    public const FIELD_PRODUCER = 'producer';
    public const FIELD_TITLE = 'title';
    public const FIELD_DIRECTOR = 'director';
    public const FIELD_RELEASE_DATE = 'release_date';
    public const FIELD_OPENING_CRAWL = 'opening_crawl';
    public const FIELD_EPISODE_ID = 'episode_id';
    public const FIELD_CHARACTERS = 'characters';

    /**
     * @var string
     */
    private string $uid;

    /**
     * @var string
     */
    private string $producer;

    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $episodeId;

    /**
     * @var string
     */
    private string $director;

    /**
     * @var string
     */
    private string $releaseDate;

    /**
     * @var string
     */
    private string $openingCrawl;

    /**
     * @var array
     */
    private array $characters;

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
     * @param $episodeId
     * @param $producer
     * @param $title
     * @param $director
     * @param $releaseDate
     * @param $openingCrawl
     * @return FilmDto
     */
    public static function make($uid, $episodeId, $producer, $title, $director, $releaseDate, $openingCrawl, $characters): FilmDto
    {
        $instance = new self();
        $instance->uid = $uid;
        $instance->producer = $producer;
        $instance->title = $title;
        $instance->director = $director;
        $instance->releaseDate = $releaseDate;
        $instance->openingCrawl = $openingCrawl;
        $instance->episodeId = $episodeId;
        $instance->characters = $characters;
        return $instance;
    }

    /**
     * Builder static method used as object factory from a given array
     * @param array $data
     * @return FilmDto
     */
    public static function fromArray(array $data): FilmDto
    {
        if (empty($data)) {
            return new self();
        }

        $uid = ArrayUtils::keyExists(self::FIELD_UID, $data, self::UNKNOWN );
        $episodeId = ArrayUtils::keyExists(self::FIELD_EPISODE_ID, $data, self::UNKNOWN );
        $producer = ArrayUtils::keyExists(self::FIELD_PRODUCER, $data, self::UNKNOWN );
        $title = ArrayUtils::keyExists(self::FIELD_TITLE, $data, self::UNKNOWN );
        $director = ArrayUtils::keyExists(self::FIELD_DIRECTOR, $data, self::UNKNOWN );
        $releaseDate = ArrayUtils::keyExists(self::FIELD_RELEASE_DATE, $data, self::UNKNOWN );
        $openingCrawl = ArrayUtils::keyExists(self::FIELD_OPENING_CRAWL, $data, self::UNKNOWN );
        $characters = ArrayUtils::keyExists(self::FIELD_CHARACTERS, $data, self::UNKNOWN );

        return self::make(
            $uid,
            $episodeId,
            $producer,
            $title,
            $director,
            $releaseDate,
            $openingCrawl,
            $characters
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
            self::FIELD_PRODUCER => $this->producer,
            self::FIELD_TITLE => $this->title,
            self::FIELD_DIRECTOR => $this->director,
            self::FIELD_RELEASE_DATE => $this->releaseDate,
            self::FIELD_OPENING_CRAWL => $this->openingCrawl,
            self::FIELD_EPISODE_ID => $this->episodeId,
            self::FIELD_CHARACTERS => $this->characters,
        ];
    }

    /**
     * @return string film uid
     */
    public function getUid(): string {
        return $this->uid;
    }

    /**
     * @return string film producer
     */
    public function getProducer(): string
    {
        return $this->producer;
    }

    /**
     * @return string film title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string film episode id
     */
    public function getEpisodeId(): string
    {
        return $this->episodeId;
    }

    /**
     * @return string film director
     */
    public function getDirector(): string
    {
        return $this->director;
    }

    /**
     * @return string film release date
     */
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    /**
     * @return string film opening crawl
     */
    public function getOpeningCrawl(): string
    {
        return $this->openingCrawl;
    }
}
