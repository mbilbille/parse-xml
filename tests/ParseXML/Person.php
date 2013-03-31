<?php

class Person
{
  private $firstname;
  private $lastname;
  private $addresses;

  public function __construct($firstname = '', $lastname = '', $addresses = array(), $height = array())
  {
    $this->firstname = $firstname;
    $this->lastname = $lastname;
    $this->addresses = $addresses;
    $this->height = $height;
  }

  public function __get($name)
  {
    return $this->$name;
  }

  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
  }

  public function setLastname($lastname)
  {
    $this->lastname = $lastname;
  }

  public function makeAddress(){
    $this->addresses[] = array(
      'thoroughfare' => '',
      'locality' => '',
      'postalcode' => '',
      'country' => '',
      );
  }

  public function setThoroughfare($thoroughfare)
  {
    $address = &$this->addresses[count($this->addresses) - 1];
    $address['thoroughfare'] = $thoroughfare; 
  }

  public function setLocality($locality)
  {
    $address = &$this->addresses[count($this->addresses) - 1];
    $address['locality'] = $locality; 
  }

  public function setPostalcode($postalcode)
  {
    $address = &$this->addresses[count($this->addresses) - 1];
    $address['postalcode'] = $postalcode; 
  }

  public function setCountry($country)
  {
    $address = &$this->addresses[count($this->addresses) - 1];
    $address['country'] = $country; 
  }

  public function setHeight($height, $attributes = array())
  {
    $this->height[$attributes['unit']] = $height;
  }

}
