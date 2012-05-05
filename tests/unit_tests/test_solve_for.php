<?php
require_once('../ExpressionExpander.php');
require_once('SetupMethods.php');

class TestSolveFor extends UnitTestCase{
    var $expression_expander;

    function testSolveForOneDepth(){
        $this->assertEqual($this->expression_expander->solveFor('c') . '', 2*3.1415*$this->expression_expander->getVariable('r') . '');
    }

    function testSolveForCircumference(){
        $this->assertEqual($this->expression_expander->solveFor('c') . '', 2*3.1415*$this->expression_expander->getVariable('r') . '');
    }

    function testVariableStartsWithOtherVariable(){
        $this->expression_expander->addVariables(array('r2' => 5));
        $this->expression_expander->addEquations(array('d2' => 'r*r2*d'));
    }
    function setUp(){
        $this->expression_expander = new ExpressionExpander();
        SetupMethods::setupForCircles($this->expression_expander);
    }
}