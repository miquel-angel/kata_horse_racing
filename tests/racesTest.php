<?php
require_once '../src/Races/races.php';

/***
 * Class to test races
 */
class racesTest extends PHPUnit_Framework_TestCase
{
    /**
     * The object to test.
     *
     * @var racers
     */
    private $obj;

    /**
     * Initialize the obj to test.
     */
    public function setUp()
	{
		$this->obj = new races();
	}

    /**
     * Tests that when no races occurs the result is empty.
     */
    public function testEmptyResultWhenNoRacesOccurs()
	{
        $this->assertEmpty( $this->obj->getClassification( array() ),
            'When we don\'t pass any result of races, should return an empty result'  );
	}

    /**
     * Tests that when we pass a result with only one race, but is empty, the result should be empty.
     */
    public function testClassificationWithOnlyOneRaceOfTypeTrialButEmpty()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array()
            )
        );
        $this->assertEmpty( $this->obj->getClassification( $races ),
            'When we  pass a result of races, but with empty races, should return an empty result'  );
    }

    /**
     * Tests that when we pass a result with only one race, but is empty, the result should be empty.
     */
    public function testClassificationWithOnlyOneRaceOfTypeClassicButEmpty()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_CLASSIC,
                'results'  => array()
            )
        );
        $this->assertEmpty( $this->obj->getClassification( $races ),
            'When we  pass a result of races, but with empty races, should return an empty result'  );
    }

    /**
     * Tests that when we pass a result with only one race, the result should be the points that give the trial version.
     */
    public function testClassificationWithOnlyOneRaceOfTypeTrial()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array(
                    'first'     => 'horse 1',
                    'second'    => 'horse 7',
                    'third'     => 'horse 10',
                    'fourth'    => 'horse 8',
                    'fifth'     => 'horse 13'
                )
            )
        );

        $final_classification = array(
            'horse 1'   => 10,
            'horse 7'   => 7,
            'horse 10'  => 3,
            'horse 8'   => 1,
            'horse 13'  => 0
        );

        $this->assertEquals( $final_classification, $this->obj->getClassification( $races ),
            'With only one race should return the same points that are given in the type trial.'  );
    }

    /**
     * Test when only has one race of the type classic.
     */
    public function testClassificationWithOnlyOneRaceOfTypeClassic()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_CLASSIC,
                'results'  => array(
                    'first'     => 'horse 1',
                    'second'    => 'horse 7',
                    'third'     => 'horse 10',
                    'fourth'    => 'horse 8',
                    'fifth'     => 'horse 13'
                )
            )
        );

        $final_classification = array(
            'horse 1'   => 25,
            'horse 7'   => 18,
            'horse 10'  => 10,
            'horse 8'   => 2,
            'horse 13'  => 0
        );

        $this->assertEquals( $final_classification, $this->obj->getClassification( $races ),
            'With only one race should return the same points that are given in the type classic.'  );
    }

    /**
     * Tests that sort correctly in function of the points.
     */
    public function testClassificationWithTwoRacesOfTheSameType()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array(
                    'first'     => 'horse 1',
                    'second'    => 'horse 7',
                    'third'     => 'horse 10',
                    'fourth'    => 'horse 8',
                    'fifth'     => 'horse 13'
                )
            ),
            'Second race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array(
                    'first'     => 'horse 21',
                    'second'    => 'horse 1',
                    'third'     => 'horse 13',
                    'fourth'    => 'horse 10',
                    'fifth'     => 'horse 8'
                )
            )
        );


        $final_classification = array(
            'horse 1'   => 17,
            'horse 21'  => 10,
            'horse 7'   => 7,
            'horse 10'  => 4,
            'horse 13'  => 3,
            'horse 8'   => 1
        );

        $this->assertSame( $final_classification, $this->obj->getClassification( $races ),
            'When have 2 races should sort the result correctly.'  );
    }

    /**
     * Tests that sort correctly in function of the points and calculate correctly the values from different types of races.
     */
    public function testClassificationWithTwoRacesOfDifferent()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array(
                    'first'     => 'horse 1',
                    'second'    => 'horse 7',
                    'third'     => 'horse 10',
                    'fourth'    => 'horse 8',
                    'fifth'     => 'horse 13'
                )
            ),
            'Second race'    => array(
                'type'  => races::TYPE_CLASSIC,
                'results'  => array(
                    'first'     => 'horse 21',
                    'second'    => 'horse 1',
                    'third'     => 'horse 13',
                    'fourth'    => 'horse 10',
                    'fifth'     => 'horse 8'
                )
            )
        );


        $final_classification = array(
            'horse 1'   => 28,
            'horse 21'  => 25,
            'horse 13'  => 10,
            'horse 7'   => 7,
            'horse 10'  => 5,
            'horse 8'   => 1
        );

        $this->assertSame( $final_classification, $this->obj->getClassification( $races ),
            'When have 2 races should sort the result correctly.'  );
    }
    /**
     * Tests that sort correctly in function of the points and calculate correctly the values from different types of races,
     * and apply one penalty to a one horse.
     */
    public function testClassificationWithTwoRacesOfDifferentAndPenaltyToAHorse()
    {
        $races = array(
            'First race'    => array(
                'type'  => races::TYPE_TRIAL,
                'results'  => array(
                    'first'     => 'horse 9',
                    'second'    => 'horse 21',
                    'third'     => 'horse 2'
                )
            ),
            'Second race'    => array(
                'type'  => races::TYPE_CLASSIC,
                'results'  => array(
                    'first'     => 'horse 21',
                    'second'    => 'horse 9',
                    'third'     => 'horse 4',
                    'fourth'    => 'horse 7',
                    'fifth'     => 'horse 13'
                )
            )
        );

        $penalties = array(
            'horse 21' => 3
        );

        $final_classification = array(
            'horse 21'  => 29,
            'horse 9'   => 28,
            'horse 4'   => 10,
            'horse 2'   => 3,
            'horse 7'   => 2,
            'horse 13'  => 0
        );

        $this->assertSame( $final_classification, $this->obj->getClassification( $races, $penalties ),
            'When have 2 races should sort the result correctly.'  );
    }

}
