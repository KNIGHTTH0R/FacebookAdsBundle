<?php

namespace Werkspot\FacebookAdsBundle\Model\Insight;

use FacebookAds\Object\Fields\InsightsFields;
use Werkspot\FacebookAdsBundle\Model\Insight\Enum\ActionReportTime;
use Werkspot\FacebookAdsBundle\Model\Insight\Enum\DatePreset;
use Werkspot\FacebookAdsBundle\Model\Insight\Enum\Field;
use Werkspot\FacebookAdsBundle\Model\Insight\Enum\Level;
use Werkspot\FacebookAdsBundle\Model\Insight\Params\TimeRange;

class Params
{
    /**
     * @var InsightsFields[]
     */
    private $fields = [];

    /**
     * @var TimeRange
     */
    private $timeRange;

    /**
     * @var ActionReportTime
     */
    private $actionReportTime;

    /**
     * @var DatePreset
     */
    private $datePreset;

    /**
     * @var bool
     */
    private $defaultSummary;

    /**
     * @var Level
     */
    private $level;

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[$field->getValue()] = $field->getValue();
    }

    /**
     * @param Field $field
     */
    public function removeField(Field $field)
    {
        unset($this->fields[$field->getValue()]);
    }

    /**
     * @param string $fields
     */
    public function addFieldsFromString($fields)
    {
        $fields = explode(',', preg_replace('/\s+/', '', $fields));
        $availableFields = Field::getValidOptions();
        foreach ($fields as $field) {
            if (in_array($field, $availableFields)) {
                $this->fields[$field] = $field;
            }
        }
    }

    public function addAllFields()
    {
        $availableFields = Field::getValidOptions();
        foreach ($availableFields as $field) {
            $this->fields[$field] = $field;
        }
    }

    /**
     * @param TimeRange $timeRange
     */
    public function setTimeRange(TimeRange $timeRange)
    {
        $this->timeRange = $timeRange;
        $this->datePreset = null;
    }

    /**
     * @param DatePreset $datePreset
     */
    public function setDatePreset(DatePreset $datePreset)
    {
        $this->datePreset = $datePreset;
        $this->timeRange = null;
    }

    /**
     * @param ActionReportTime $actionReportTime
     */
    public function setActionReportTime(ActionReportTime $actionReportTime)
    {
        $this->actionReportTime = $actionReportTime;
    }

    /**
     * @param bool $defaultSummary
     */
    public function setDefaultSummary($defaultSummary)
    {
        $this->defaultSummary = (boolean) $defaultSummary;
    }

    /**
     * @param Level $level
     */
    public function setLevel(Level $level)
    {
        $this->level = $level;
    }

    public function getParamsArray()
    {
        $params = [];

        $params['fields'] = implode(', ', $this->fields);

        if ($this->timeRange) {
            $params['time_range'] = $this->timeRange->getParamsArray();
        }

        if ($this->datePreset) {
            $params['date_preset'] = $this->datePreset->getValue();
        }

        if ($this->actionReportTime) {
            $params['action_report_time'] = $this->actionReportTime->getValue();
        }

        if ($this->defaultSummary !== null) {
            $params['default_summary'] = $this->defaultSummary;
        }

        if ($this->level) {
            $params['level'] = $this->level->getValue();
        }

        return $params;
    }
}