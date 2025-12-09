<?php

namespace App\Http\Dtos;

use App\Lib\Utils\ArrayUtils;

/**
 * MetricDto immutable class representing a Metric.
 */
class MetricDto extends AbstractDto
{
    public const FIELD_METRIC_NAME = 'name';
    public const FIELD_METRIC_VALUE = 'value';

    public const FIELD_METRIC_URI = 'uri';

    /**
     * @var string $metricName
     */
    private string $metricName;

    /**
     * @var string $metricValue
     */
    private string $metricValue;

    /**
     * @var string $metricUri
     */
    private string $metricUri;

    /**
     * Object static factory method
     * @param $metricName
     * @param $metricValue
     * @return MetricDto
     */
    public static function make($metricName, $metricValue, $metricUri): MetricDto {
        $instance = new self();
        $instance->metricName = $metricName;
        $instance->metricValue = $metricValue;
        $instance->metricUri = $metricUri;
        return $instance;
    }

    public static function fromArray(array $data): MetricDto
    {
        if (empty($data)) {
            return new self();
        }

        $metricName= ArrayUtils::keyExists(self::FIELD_METRIC_NAME, $data, self::UNKNOWN );
        $metricValue = ArrayUtils::keyExists(self::FIELD_METRIC_VALUE, $data, self::UNKNOWN );
        $metricUri = ArrayUtils::keyExists(self::FIELD_METRIC_URI, $data, self::UNKNOWN );

        return self::make(
            $metricName,
            $metricValue,
            $metricUri
        );
    }

    /**
     * @return array the array representation
     */
    public function toArray(): array {
        return [
            self::FIELD_METRIC_NAME => $this->metricName,
            self::FIELD_METRIC_VALUE => $this->metricValue,
            self::FIELD_METRIC_URI => $this->metricUri,
        ];
    }

    /**
     * @return string metric name
     */
    public function getMetricName(): string
    {
        return $this->metricName;
    }

    /**
     * @return string metric value
     */
    public function getMetricValue(): string
    {
        return $this->metricValue;
    }

    /**
     * @return string metric uri
     */
    public function getMetricUri(): string
    {
        return $this->metricUri;
    }

}
