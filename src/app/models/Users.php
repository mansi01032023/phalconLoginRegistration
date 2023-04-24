<?php

use Phalcon\Mvc\Model;

// this is the user class which extends model
class Users extends Model
{
    public $id;
    public $name;
    public $email;
    public $password;
}
