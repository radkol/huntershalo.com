<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author raddy
 */
interface DataImporter {
   
   const FILE_PATH = "site/import/data/"; 
    
   function delimiter();
   
   function import();
   
   function type();
   
   function extension();
   
   function mapping();
}
