<?php 

try
{
	$baglanti = new PDO("mysql:host=localhost; dbname=universite", "root", "12345678");
}
catch (Exception $e)
{
	$baglanti = null;
}


?>