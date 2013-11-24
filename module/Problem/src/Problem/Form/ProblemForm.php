<?php

namespace Problem\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Problem\Form\TestCaseFieldset;

class ProblemForm extends Form {

    public function __construct($name = null) {
        parent::__construct('problem');

        $problemID = new Element\Hidden('problem_id');

        $problemName = new Element\Text('problem_name');
        $problemName->setAttribute('placeholder', 'Nombre del problema');

        $author = new Element\Text('problem_author');
        $author->setAttribute('placeholder', 'Nombre del autor');

        $mainDescription = new Element\File('main_description');
        $mainDescription->setAttribute('accept', '.tex');
        $inputDesc = new Element\File('input_description');
        $inputDesc->setAttribute('accept', '.tex');
        $outputDesc = new Element\File('output_description');
        $outputDesc->setAttribute('accept', '.tex');
        $inputExample = new Element\File('input_example');
        $inputExample->setAttribute('accept', '.tex');
        $outputExample = new Element\File('output_example');
        $outputExample->setAttribute('accept', '.tex');

        $time = new Element\Text('time_constraint');
        $memory = new Element\Text('memory_constraint');
        $source = new Element\Text('source_constraint');

        $type = new Element\Radio('is_simple');
        $type->setValueOptions(array(
            'TRUE' => array(
                'label' => 'Simple',
                'value' => 'TRUE',
                'attributes' => array(
                    'id' => 'simple',
                ),
            ),
            'FALSE' => array(
                'label' => 'Solucion Multiple',
                'value' => 'FALSE',
                'attributes' => array(
                    'id' => 'mult',
                ),
            ),
        ));
        $type->setValue('TRUE');
        $type->setAttribute('onclick', 'checkType()');

        $compareType = new Element\Radio('compare_type');
        $compareType->setValueOptions(array(
            'STRICT' => 'Estricta',
            'TOKEN' => 'Omitir token',
        ));
        $compareType->setValue('STRICT');

        $file = new Element\File('file');
        
        $images = new Element\Collection('images');
        $images->setLabel("Imagenes");
        $images->setCount(0);
        $images->allowAdd(true);
        $images->allowRemove(true);
        $images->setShouldCreateTemplate(true);
        $images->setTargetElement($file);

        $tests = new Element\Collection('tests');
        $tests->setLabel("Pruebas");
        $tests->setCount(1);
        $tests->allowAdd(true);
        $tests->allowRemove(true);
        $tests->setShouldCreateTemplate(true);
        $tests->setTargetElement(new TestCaseFieldset());

        $submit = new Element\Submit('submit');
        $submit->setValue('Proponer');
        $submit->setAttribute('class', 'button');
        $this->setAttribute('onsubmit', 'return gradeCheck();');

        $this->add($problemID);
        $this->add($problemName);
        $this->add($author);
        $this->add($time);
        $this->add($memory);
        $this->add($source);
        $this->add($mainDescription);
        $this->add($inputDesc);
        $this->add($outputDesc);
        $this->add($inputExample);
        $this->add($outputExample);
        $this->add($type);
        $this->add($images);
        $this->add($tests);
        $this->add($compareType);
        $this->add($submit);
    }

}
