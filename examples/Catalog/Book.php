<?php

class Book
{
  private $id;
  private $author;
  private $genres;
  private $price;
  private $publish_date;
  private $description;

  public function __construct($attributes)
  {
    $this->id = isset($attributes["id"]) ? $attributes["id"] : 0;
    $this->author = '';
    $this->genres = array();
    $this->price = 0;
    $this->publish_date = date('Y-m-d');
    $this->description = '';
  }

  public function __get($name)
  {
    return $this->$name;
  }

  public function setAuthor($author)
  {
    $this->author = $author;
  }

  public function setGenre($author)
  {
    $this->genres[] = $author;
  }

  public function setPrice($price)
  {
    $this->price = is_numeric($price) ? $price : 0;
  }

  public function setPublishDate($date)
  {
    $this->publish_date = $date;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }
}
