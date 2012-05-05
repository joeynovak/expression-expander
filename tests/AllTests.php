<?php
require_once('../simpletest/autorun.php');

class TestSuite_All extends TestSuite{
    function TestSuite_All(){
        $this->TestSuite('All Tests');
        $unit_test_directory_path = dirname(__FILE__) . '/unit_tests';
        $files = scandir($unit_test_directory_path);
        foreach($files as $file){
            if(is_file($unit_test_directory_path . '/' . $file)){
                $this->addFile($unit_test_directory_path . '/' . $file);
            }
        }
    }
}
