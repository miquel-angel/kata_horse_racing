<?php
/**
 * Class Races, to obtain the global classifications in a horse races.
 */
class races
{
    /**
     * The string to determine a trial race.
     *
     * @var string
     */
    const TYPE_TRIAL = 'trial';

    /**
     * The string to determine a classic race.
     *
     * @var string
     */
    const TYPE_CLASSIC = 'classic';

    /**
     * The points for each type of race.
     *
     * @var array
     */
    private $point_by_type = array(
        'trial' => array(
            10,
            7,
            3,
            1,
        ),
        'classic'   => array(
            25,
            18,
            10,
            2
        )
    );

    /**
     * Obtain the global classification for a concrete races.
     *
     * @param array $races_results The results for all the races.
     * @param array $penalties Array with the penalties for each horse.
     * @return array
     */
    public function getClassification( array $races_results, array $penalties = array() )
    {
        $final_classification = array();

        foreach ( $races_results as $race )
        {
            $position = 0;

            foreach ( $race['results'] as $jokey )
            {
                if ( !isset( $final_classification[$jokey] ) )
                {
                    $final_classification[$jokey] = 0;
                }

                $final_classification[$jokey] += isset( $this->point_by_type[$race['type']][$position] ) ?
                    $this->point_by_type[$race['type']][$position++] : 0;
            }
        }

        foreach ( $penalties as $jokey => $penalty )
        {
            if ( isset( $final_classification[$jokey] ) )
            {
                $final_classification[$jokey] -= $penalty;
            }
        }

        arsort( $final_classification );

        return $final_classification;
    }
}
?>
