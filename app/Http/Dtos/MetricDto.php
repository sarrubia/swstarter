<?php

namespace App\Http\Dtos;

/**
 * MetricDto immutable class representing a Metric.
 */
class MetricDto extends AbstractDto
{
    public const FIELD_METRIC_NAME = 'metric_name';
    public const FIELD_METRIC_VALUE = 'metric_value';

    /**
     * @var string $metricName
     */
    private string $metricName;

    /**
     * @var string $metricValue
     */
    private string $metricValue;

    /**
     * Object static factory method
     * @param $metricName
     * @param $metricValue
     * @return MetricDto
     */
    public static function make($metricName, $metricValue): MetricDto {
        $instance = new self();
        $instance->metricName = $metricName;
        $instance->metricValue = $metricValue;
        return $instance;
    }

    /**
     * @return array the array representation
     */
    public function toArray(): array {
        return [
            self::FIELD_METRIC_NAME => $this->metricName,
            self::FIELD_METRIC_VALUE => $this->metricValue
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
}
