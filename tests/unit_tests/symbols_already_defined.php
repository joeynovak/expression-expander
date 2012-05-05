<?php
include_once('../ExpressionExpander.php');
require_once('SetupMethods.php');

class SymbolsAlreadyDefined extends UnitTestCase{
    var $expression_expander;


    function testAddDefinedVariable(){
        try{
            $this->expression_expander->addVariables(array('r' => 5));
            $this->fail("Expected exception");
        } catch(Exception $e){
        }
    }

    function testAddDefinedConstant(){
        try{
            $this->expression_expander->addConstants(array('r' => 5));
            $this->fail("Expected exception");
        } catch(Exception $e){
        }
    }

    function testAddDefinedEquation(){
        try{
            $this->expression_expander->addEquations(array('r' => '2*d'));
            $this->fail("Expected exception");
        } catch(Exception $e){
        }
    }

    function testAddVariableDefinedAsEquation(){
        try{
            $this->expression_expander->addVariables(array('d' => 5));
            $this->fail("Expected exception");
        } catch(Exception $e){
        }
    }

    function testAddVariableDefinedAsConstant(){
        try{
            $this->expression_expander->addVariables(array('d' => 6));
            $this->fail("Expected exception");
        } catch(Exception $e){
        }
    }

    function setUp(){
            $this->expression_expander = new ExpressionExpander();
            SetupMethods::setupForCircles($this->expression_expander);
    }

}