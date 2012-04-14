<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsPngConverter.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to a PNG image for display purposes.
*
* Author: Tom McDonnell 2010-02-13.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../../library/tom/php/utils/Utils_validator.php';
require_once dirname(__FILE__) . '/../mapper_circle/SpectrumRowsToCircleMapper.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsPngConverter
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
   public static function toPngFromFile($dimensions, $cellDimensions, $file)
   {
      Utils_validator::checkArray($dimensions    , array('width' => 'int', 'height' => 'int'));
      Utils_validator::checkArray($cellDimensions, array('width' => 'int', 'height' => 'int'));

      $cellWidth  = $cellDimensions['width' ];
      $cellHeight = $cellDimensions['height'];

      $imageMinX = 0;
      $imageMinY = 0;
      $imageMaxX = $dimensions['width' ] * $cellWidth;
      $imageMaxY = $dimensions['height'] * $cellHeight;

      header('Content-type: image/png');
      $image = imagecreatetruecolor($imageMaxX, $imageMaxY);

      for ($rowNo = 0; $rowNo < $dimensions['height']; ++$rowNo)
      {
         for ($colNo = 0; $colNo < $dimensions['width']; ++$colNo)
         {
            $r = (int)fread($file, 3);
            $g = (int)fread($file, 3);
            $b = (int)fread($file, 3);

            $color = self::getColor($image, $r, $g, $b);

            $minX = $imageMinX + $colNo * $cellWidth;
            $minY = $imageMinY + $rowNo * $cellHeight;
            $maxX = $minX + $cellWidth;
            $maxY = $minY + $cellHeight;

            imagefilledrectangle($image, $minX, $minY, $maxX, $maxY, $color);
         }
      }

      imagepng($image);
      imagedestroy($image);
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function getColor($image, $r, $g, $b)
   {
      static $colorsByIndex = array();

      $index = 1000000 * $r + 1000 * $g + $b;

      if (!array_key_exists($index, $colorsByIndex))
      {
         $colorsByIndex[$index] = imagecolorallocate($image, $r, $g, $b);
      }

      return $colorsByIndex[$index];
   }
}

/*******************************************END*OF*FILE********************************************/
?>
