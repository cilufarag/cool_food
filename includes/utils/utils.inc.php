<?php
  
  /**
   * Function validating if value pass custom validation standards
   * @parameters $value, $validation, $errorMessage, and string of &$errors
   * if validation is false insert the $errorMessage to $errors array
   */

  function checkIfValid($value, $validation, $errorMessage, &$errors) {
    if(!filter_var($value, $validation)) {
      array_push($errors, $errorMessage);
    }
  }

?>

