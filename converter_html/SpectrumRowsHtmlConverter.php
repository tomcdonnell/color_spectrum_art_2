<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsHtmlConverter.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to HTML text for display purposes.
*
* Author: Tom McDonnell 2010-01-16.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../library/tom/php/utils/Utils_validator.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsHtmlConverter
{
   // Public functions. /////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   public function __construct()
   {
      throw new Exception('This class is not intended to be instantiated.');
   }

   /*
    *
    */
   public static function toHtmlFromFile($dimensions, $cellDimensions, $file)
   {
      Utils_validator::checkArray($dimensions    , array('width' => 'int', 'height' => 'int'));
      Utils_validator::checkArray($cellDimensions, array('width' => 'int', 'height' => 'int'));

      $html = '<table>';

      for ($rowNo = 0; $rowNo < $dimensions['height']; ++$rowNo)
      {
         $html .= '<tr>';

         for ($colNo = 0; $colNo < $dimensions['width']; ++$colNo)
         {
            $r = fread($file, 3);
            $g = fread($file, 3);
            $b = fread($file, 3);

            $styleR = (int)($r);
            $styleG = (int)($g);
            $styleB = (int)($b);

            $styleStr =
            (
               "width: {$cellDimensions['width']}px;" .
               " height: {$cellDimensions['height']}px;" .
               " background: rgb($styleR,$styleG,$styleB);"
            );

            $html .= "<td style='$styleStr'></td>";
         }

         $html .= '</tr>';
      }

      $html .= '</table>';

      return $html;
   }
}

/*******************************************END*OF*FILE********************************************/
?>
