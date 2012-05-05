<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et co=100 go-=b
*
* Filename: "spectrum_image_png.php"
*
* Project: Spectrum Color Art.
*
* Purpose: This file should be used as an image.
*
* Author: Tom McDonnell 2009-11-08.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../lib_tom/php/utils/UtilsValidator.php';
require_once dirname(__FILE__) . '/../SpectrumGenerator.php';
require_once dirname(__FILE__) . '/SpectrumRowsPngConverter.php';

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   UtilsValidator::checkArray
   (
      $_GET, array
      (
         'filename'    => 'string'     ,
         'imageWidth'  => 'ctype_digit',
         'imageHeight' => 'ctype_digit',
         'cellWidth'   => 'ctype_digit',
         'cellHeight'  => 'ctype_digit'
      )
   );

   $file = fopen($_GET['filename'], 'rb');

   SpectrumRowsPngConverter::toPngFromFile
   (
      array('width'  => (int)$_GET['imageWidth'], 'height' => (int)$_GET['imageHeight']),
      array('width'  => (int)$_GET['cellWidth' ], 'height' => (int)$_GET['cellHeight' ]),
      $file
   );
}
catch (Exception $e)
{
   echo $e->getMessage();
}

/*******************************************END*OF*FILE********************************************/
?>
