<?php

class Person
{
  private $firstname;
  private $lastname;

  public function __construct($firstname = '', $lastname = '')
  {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
  }

  public function __get($name)
  {
    return $this->$name;
  }

  public function set_firstname($firstname)
  {
    $this->firstname = $firstname;
  }

  public function set_lastname($lastname)
  {
    $this->lastname = $lastname;
  }
}
