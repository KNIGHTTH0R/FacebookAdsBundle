<?php
namespace Werkspot\FacebookAdsBundle\Tests\Model\AdSet\Params;

use PHPUnit_Framework_TestCase;
use Werkspot\FacebookAdsBundle\Model\AdSet\Enum\Field;
use Werkspot\FacebookAdsBundle\Model\AdSet\Params;

class ParamsTest extends PHPUnit_Framework_TestCase
{
    public function testNewParamsIsEmpty()
    {
        $params = new Params();
        $this->assertEquals('?fields=', $params->getBatchQuery());
        $this->assertEquals(['fields' => ''], $params->getParamsArray());   
    }
    
    /**
     * @dataProvider fieldEnumData
     *
     * @params Field $field
     */
    public function testAddField(Field $field)
    {
        $params = new Params();

        $params->addField($field);
        $this->assertEquals(
            ['fields' => $field->getValue()],
            $params->getParamsArray()
        );
        $this->assertEquals('?fields=' . $field->getValue(), $params->getBatchQuery());
    }

    public function testAddAllFields()
    {
        $allField = Field::getValidOptions();
        $params = new Params();

        $params->addAllFields();
        $this->assertEquals(
            ['fields' => implode(', ', $allField)],
            $params->getParamsArray()
        );
    }

    public function testAddFieldsFromString()
    {
        $oneParameter = Field::BUDGET_REMAINING;
        $params = new Params();

        $params->addFieldsFromString($oneParameter);
        $this->assertEquals(
            ['fields' => $oneParameter],
            $params->getParamsArray()
        );

        $twoParameter = Field::PROMOTED_OBJECT . ', ' . Field::AD_SET_SCHEDULE;
        $params = new Params();

        $params->addFieldsFromString($twoParameter);
        $this->assertEquals(
            ['fields' => $twoParameter],
            $params->getParamsArray()
        );
    }

    /**
     * @dataProvider fieldEnumData
     *
     * @params Field $field
     */
    public function testRemoveField(Field $field)
    {
        $params = new Params();
        $params->addField($field);
        $this->assertEquals(
            ['fields' => $field->getValue()],
            $params->getParamsArray()
        );
        $params->removeField($field);

        $this->assertEquals(
            ['fields' => ''],
            $params->getParamsArray()
        );
    }

    /**
     * @dataProvider fieldEnumData
     *
     * @params Field $field
     */
    public function testRemoveFieldWithAdditionalField(Field $field)
    {
        $defaultField = Field::get(Field::ACCOUNT_ID);
        if ($field !== $defaultField) {
            $parameters = $field->getValue() . ', ' . $defaultField->getValue();
            $params = new Params();
            $params->addFieldsFromString($parameters);
            $this->assertEquals(
                ['fields' => $parameters],
                $params->getParamsArray()
            );
            $params->removeField($field);

            $this->assertEquals(
                ['fields' => $defaultField->getValue()],
                $params->getParamsArray()
            );
        }
    }
    
    public function testLimit()
    {
        $this->markTestIncomplete(
            'Waiting for issue to be resolved by Facebook. ' .
            'see https://github.com/facebook/facebook-php-ads-sdk/issues/193 for more details'
        );
        $limit = 11;
        $params = new Params();

        $params->setLimit($limit);
        $this->assertEquals(['fields' => '', 'limit' => $limit], $params->getParamsArray());
        $this->assertEquals('?fields=&limit=' . $limit, $params->getBatchQuery());
    }

    /**
     * @return array
     */
    public function fieldEnumData()
    {
        $result = [];
        $fields = Field::getValidOptions();
        foreach ($fields as $key => $field) {
            $result[$field] = [Field::get($field)];
        }

        return $result;
    }
}