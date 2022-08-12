<?php

namespace src\pocketmine\timer;

final class TimerException extends \Exception{
     
     private string $message;
    
     public function __construct(string $message){
      $this->message = $message;
  }
  
     public function __tostring() : string{
      return "Error: " . $this->message . " in line " . $this->line . " and in the file " . $this->file;
  }
}
