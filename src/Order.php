<?php

namespace BooStudio\BooShip;

class Order
{
    //To Details
    public $to_name = "";
    public $to_phone = "";
    public $to_email = "";
    public $to = "";
    public $to_Suburb = "";
    public $to_State = "";
    public $to_Address = "";
    

    //From Details
    public $From_name = array();
    public $From = "";
    public $From_Suburb = "";
    public $From_State = "";
    public $From_Address = "";
    public $From_email = "";
    public $From_phone = "";
    

    //order reference

    public $Order_reference = "";

    //items
    public $items = array();
    
}
