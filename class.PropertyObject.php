<?php
  abstract class PropertyObject {
   
    protected $propertyTable = array();     //stores name/value pairs
                                            //that hook properties to
                                            //database field names
                                           
    protected $changedProperties = array(); //List of properties that
                                            //have been modified
   
    protected $data;                        //Actual data from
                                            //the database
                                           
    protected $errors = array();            //Any validation errors
                                            //that might have occurred
                                          
   
    public function __construct($arData) {
      $this->data = $arData;
    }
   
    function __get($propertyName) {
      if(!array_key_exists($propertyName, $this->propertyTable))
        throw new Exception("Invalid property \"$propertyName\"!");
     
      if(method_exists($this, 'get' . $propertyName)) {
        return call_user_func(array($this, 'get' . $propertyName));
      } else {
        return $this->data[$this->propertyTable[$propertyName]];
      }
    }
 
    function __set($propertyName, $value) {
      if(!array_key_exists($propertyName, $this->propertyTable))
        throw new Exception("Invalid property \"$propertyName\"!");
     
      if(method_exists($this, 'set' . $propertyName)) {
        return call_user_func(
                              array($this, 'set' . $propertyName),
                              $value
                             );
      } else {
       
        //If the value of the property really has changed
        //and it's not already in the changedProperties array,
        //add it. 
        if($this->propertyTable[$propertyName] != $value &&
           !in_array($propertyName, $this->changedProperties)) {
          $this->changedProperties[] = $propertyName;
        }
       
        //Now set the new value
        $this->data[$this->propertyTable[$propertyName]] = $value;
       
      }
    }
     
    function validate() {

    }
 
  }
?>