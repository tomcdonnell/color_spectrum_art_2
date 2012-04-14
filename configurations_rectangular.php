<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "configurations_rectangular.php"
*
* Project: Programming Tools - Spectrum Generator.
*
* Purpose: Configurations for viewing in rectangular mode.
*
* Author: Tom McDonnell 2010-02-14.
*
\**************************************************************************************************/

// Global variables. ///////////////////////////////////////////////////////////////////////////////

$configurations = array
(
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => false, 'n_subrows' => 50),
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' =>  '111', 'dual' => true , 'n_subrows' => 50),

   array('includeReflection' => false, 'filter' => '!111', 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' => '!111', 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' => '!111', 'dual' => false, 'n_subrows' => 50),
   array('includeReflection' => false, 'filter' => '!111', 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' => '!111', 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' => '!111', 'dual' => true , 'n_subrows' => 50),

   array('includeReflection' => false, 'filter' =>  null , 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' =>  null , 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' =>  null , 'dual' => false, 'n_subrows' => 50),
   array('includeReflection' => false, 'filter' =>  null , 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => false, 'filter' =>  null , 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => false, 'filter' =>  null , 'dual' => true , 'n_subrows' => 50),

   array('includeReflection' => true , 'filter' =>  '111', 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' =>  '111', 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' =>  '111', 'dual' => false, 'n_subrows' => 25),
   array('includeReflection' => true , 'filter' =>  '111', 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' =>  '111', 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' =>  '111', 'dual' => true , 'n_subrows' => 50),

   array('includeReflection' => true , 'filter' => '!111', 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' => '!111', 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' => '!111', 'dual' => false, 'n_subrows' => 25),
   array('includeReflection' => true , 'filter' => '!111', 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' => '!111', 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' => '!111', 'dual' => true , 'n_subrows' => 50),

   array('includeReflection' => true , 'filter' =>  null , 'dual' => false, 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' =>  null , 'dual' => false, 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' =>  null , 'dual' => false, 'n_subrows' => 25),
   array('includeReflection' => true , 'filter' =>  null , 'dual' => true , 'n_subrows' =>  1),
   array('includeReflection' => true , 'filter' =>  null , 'dual' => true , 'n_subrows' =>  2),
   array('includeReflection' => true , 'filter' =>  null , 'dual' => true , 'n_subrows' => 25)
);

$cellDimensionsByConfigurationIndex = array
(
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),

   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),

   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),

   array('width' => 50, 'height' =>  50),
   array('width' => 25, 'height' =>  25),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  25),
   array('width' => 50, 'height' =>   2),

   array('width' => 50, 'height' =>  50),
   array('width' => 25, 'height' =>  25),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 25, 'height' =>  25),
   array('width' => 50, 'height' =>   2),

   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>  50),
   array('width' => 50, 'height' =>   2)
);

/*******************************************END*OF*FILE********************************************/
?>
