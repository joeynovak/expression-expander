<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Joey
 * Date: 5/5/12
 * Time: 12:25 PM
 * To change this template use File | Settings | File Templates.
 */
class SetupMethods
{
    public static function setupForCircles($expression_expander){
        $expression_expander->addConstants(
            array(
                'pi' => 3.1415
            )
        );

        $expression_expander->addEquations(
            array(
                'd' => 'r*2',
                'c' => 'd*pi'
            )
        );

        $expression_expander->addVariables(
            array(
                'r' => 5
            )
        );
    }
}
