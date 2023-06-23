<?php
class Config{
	
	function getConnection(){
       return new mysqli("localhost", "root", "", "nacosvoting");
    }
}
?>