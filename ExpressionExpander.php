<?php
require_once('ExpressionException.php');

class ExpressionExpander{
    var $equations = array();

    /* Equations are in the format of
    array(
        'c' =>
            array(
                array(
                    'equation' => 'd*pi',
                    (optional) 'condition' => 'r>0'
                ),
                array(
                    'equation' => '2*pi*r*-1',
                    'condition' => 'r<=0'
                )
            ),
        'd' =>
            array(
                array(
                    'equation' => '2*pi',
                )
            ),
        ...
    );
    */

    var $constants = array();

    /* constants - MUST be equal to a number, cannot be more variables or equations (that would be an equation).
    array(
        'pi' => 3.1415,
        'e' => 2.71828,
        ....
    )
    */

    var $variables = array();

    /* variables
    array(
        'r' => 3,
        ...
    )
    */

    public function __construct(){

    }

    public function addEquations(array $equations){
        foreach($equations as $symbol => $symbol_equations){
            $this->alreadyDefined($symbol);
            if(empty($equations[0])){
                $equations[$symbol] = array(array('equation' => $symbol_equations));
            }
        }

        $this->equations = array_merge($this->equations, $equations);
        
        return $this;
    }

    public function addConstants(array $constants){
        foreach($constants as $symbol => $constant){
            $this->alreadyDefined($symbol);
        }

        $this->constants = array_merge($this->constants, $constants);
        
        return $this;
    }

    public function addVariables(array $variables){
        foreach($variables as $symbol => $variable){
            $this->alreadyDefined($symbol);
        }

        $this->variables = array_merge($this->variables, $variables);
        
        return $this;
    }

    public function solveFor($variable_name){
        if(empty($this->equations[$variable_name])){
            throw new ExpressionException("$variable_name does not have any equations defined");
        }

        $equations = $this->equations[$variable_name];

        return $this->evaluate($variable_name, array());
    }

    public function evaluate($expression, $previous_references){
        $variables = array();
        preg_match_all('/[a-zA-Z]+[a-zA-Z0-9_]*/', $expression, $variables);

        $variables = array_unique($variables);

        usort($variables, array($this, 'sort_by_strlen'));

        foreach($variables[0] as $variable){
            $temp_previous_references = $previous_references;
            $temp_previous_references[] = $variable;

            //Check for circular references...
            if(array_search($variable, $previous_references) !== false){
                throw new ExpressionException("Circular Reference! $expression - $variable - Previous References:" . implode(",", $previous_references));
            }

            if(!empty($this->constants[$variable])){
                $expression = str_replace($variable, $this->constants[$variable], $expression);
            } elseif(!empty($this->variables[$variable])){
                $expression = str_replace($variable, $this->variables[$variable], $expression);
            } elseif(!empty($this->equations[$variable])){
                $sum_of_equations = 0;
                if(count($this->equations[$variable]) == 0){
                    throw new ExpressionException("$variable has an empty entry in the equations array.");
                }

                foreach($this->equations[$variable] as $equation){
                    $condition_passes = false;
                    if(!empty($equation['condition'])){
                        $condition_passes = $this->evaluate($equation['condition'], $temp_previous_references) > 0;
                    } else {
                        $condition_passes = true;
                    }

                    if($condition_passes) $sum_of_equations+=$this->evaluate($equation['equation'], $temp_previous_references);
                }
                $expression = str_replace($variable, $sum_of_equations, $expression);
            } else {
                throw new ExpressionException("$variable is not a constant or a defined variable and there are no equations for it either.");
            }
        }

        return eval('return ' . $expression . ';');
    }

    public function getVariable($variable_name){
        return $this->variables[$variable_name];
    }

    private function alreadyDefined($symbol){
        if(!empty($this->equations[$symbol])){
            throw new ExpressionException("Symbol: $symbol already defined with an equation.");
        }
        
        if(!empty($this->constants[$symbol])){
            throw new ExpressionException("Symbol: $symbol already defined as a constant.");
        }
        
        if(!empty($this->variables[$symbol])){
            throw new ExpressionException("Symbol: $symbol already defined as a variable.");
        }
        
        return true;
    }

    private function sort_by_strlen($a,$b){
        return strlen($b)-strlen($a);
    }
}
