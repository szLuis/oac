<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
session_cache_limiter('nocache');
session_unset();

session_destroy();
header('Location:../usuarios/');
