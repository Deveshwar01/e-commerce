<?php

namespace MiddlewareSecurity;

use GlobalsFunctions\Globals;

class Security extends Globals
{
   private $currentUser;

   private $currentView;

   public function __construct(){

       $this->currentView = self::view();
       $this->currentUser = self::user();
   }

   public function checkViewAccess(){
       if(empty($this->currentView)){
           return "V-NULL";
       }

       //check access

       $values = array_values($this->currentView);
       if(in_array('public', $values)){
           return "V-PUBLIC";
       }

       if(in_array('private', $values)){
           return "V-PRIVATE";
       }
   }

   public function checkCurrentUser(){
       if(empty($this->currentUser)){
           return "U-NULL";
       }

       //check role
       if($this->currentUser['role'] === "Admin"){
           return "U-Admin";
       }

       if($this->currentUser['blocked'] === true){
           return "U-BLOCK";
       }

       if($this->currentUser['verified'] === true){
           return "V-VERIFIED";
       }
   }
}