<?php

namespace Problem\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Input;
use Zend\InputFilter\FileInput;
use Zend\Filter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator;

class Problem implements InputFilterAwareInterface {

    public $problem_id;
    public $problem_name;
    public $problem_author;
    public $problem_description;
    public $time_constraint;
    public $memory_constraint;
    public $source_constraint;
    public $is_simple;
    public $compare_type;
    public $file_in;
    public $file_out;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->problem_id = (!empty($data['problem_id'])) ? $data['problem_id'] : null;
        $this->problem_name = (!empty($data['problem_name'])) ? $data['problem_name'] : null;
        $this->problem_author = (!empty($data['problem_author'])) ? $data['problem_author'] : null;
        $this->problem_description = (!empty($data['problem_description'])) ? $data['problem_description'] : null;
        $this->time_constraint = (!empty($data['time_constraint'])) ? $data['time_constraint'] : null;
        $this->memory_constraint = (!empty($data['memory_constraint'])) ? $data['memory_constraint'] : null;
        $this->source_constraint = (!empty($data['source_constraint'])) ? $data['source_constraint'] : null;
        $this->is_simple = (!empty($data['is_simple'])) ? $data['is_simple'] : null;
        $this->compare_type = (!empty($data['compare_type'])) ? $data['compare_type'] : null;
        
        if ( is_array($data['file_in'])) {
            $this->file_in = (!empty($data['file_in'])) ? $data['file_in']['tmp_name'] : null;
        } else {
            $this->file_in = (!empty($data['file_in'])) ? $data['file_in'] : null;
        }

        if ( is_array($data['file_out'])) {
            $this->file_out = (!empty($data['file_out'])) ? $data['file_out']['tmp_name'] : null;
        } else {
            $this->file_out = (!empty($data['file_out'])) ? $data['file_out'] : null;
        }
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $lengthValidator = new Validator\StringLength(array('min' => 3, 'max' => 50));
            
            $nameValidator = new Input('problem_name');
            $nameValidator->getValidatorChain()
                    ->addValidator($lengthValidator);
//            $nameValidator->getFilterChain()
//                    ->attachByName('stringtrim')
//                    ->attachByName('alpha', array('allowwhitespace' => true));
            $validator = new Zend_Validate_Db_RecordExists(
            array(
                   'table' => 'problem',
                   'field' => 'problem_name'
                   ));
 
             if ($validator->isValid($nameValidator)) {
                 // ProblemName appears to be valid
                } else {
                // ProblemName is invalid; print the reasons
            foreach ($validator->getMessages() as $message) {
               echo "$message\n";
                }

             }
            
            

            $authorValidator = new Input('problem_author');
            $authorValidator->getValidatorChain()
                    ->addValidator($lengthValidator);
            $authorValidator->getFilterChain()
                    ->attachByName('stringtrim')
                    ->attachByName('alpha', array('allowwhitespace' => true));

            $numberValidator = new Validator\Digits();

            $timeValidator = new Input('time_constraint');
            $timeValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $timeValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $memoryValidator = new Input('memory_constraint');
            $memoryValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $memoryValidator->getFilterChain()
                    ->attachByName('stringtrim');

            $sourceValidator = new Input('source_constraint');
            $sourceValidator->getValidatorChain()
                    ->addValidator($numberValidator);
            $sourceValidator->getFilterChain()
                    ->attachByName('stringtrim');


            $descriptionValidator = new Input('problem_description');
            $descriptionValidator->getValidatorChain()
                    ->addValidator(new Validator\StringLength(array('min' => 10)));
            $descriptionValidator->getFilterChain()
                    ->attachByName('stringtrim');
            
             $valid = new Zend_Validate_NotEmpty(
                     Zend_Validate_NotEmpty::INTEGER + Zend_NotEmpty::ZERO
                     );      
            $valid->isValid($descriptionValidator);
            
            $valueSpace  = '';
            $valid->isValid($valueSpace);
            

            $fileIn = new FileInput('file_in');
            $fileIn->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile());
            $fileIn->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/fileIn',
                        'randomize' => true,
            )));
            $fileIn->addValidator('Extension', false, array('cpp', 'c', 
                'java', 'txt', 'in' => true));
            
            $fileOut = new FileInput('file_out');
            $fileOut->getValidatorChain()
                    ->addValidator(new Validator\File\UploadFile());
            $fileOut->getFilterChain()
                    ->attach(new Filter\File\RenameUpload(array(
                        'target' => './data/problems/fileOut',
                        'randomize' => true,
            )));
            $fileOut->addValidator('Extension', false, array('cpp', 'c', 'java',
                'txt', 'out' => true));   


            $inputFilter->add($nameValidator);
            $inputFilter->add($authorValidator);
            $inputFilter->add($timeValidator);
            $inputFilter->add($memoryValidator);
            $inputFilter->add($sourceValidator);
            $inputFilter->add($descriptionValidator);
            $inputFilter->add($fileIn);
            $inputFilter->add($fileOut);


            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

}