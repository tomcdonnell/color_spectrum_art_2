<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumRowsToCircleMapper.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Convert an array returned by the SpectrumGenerator to an array of equal dimensions
*          whose contents has been modified such that the rectangular image has been mapped to a
*          circle.  The top row of pixels map to the center of the circle, and the bottom row of
*          pixels map to the outer edge of the circle.
*
* Author: Tom McDonnell 2010-02-13.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/../lib_tom/php/utils/UtilsValidator.php';
require_once dirname(__FILE__) . '/../lib_tom/php/utils/UtilsString.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumRowsToCircleMapper
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
   public static function mapFromFileToFile
   (
      $fileSrc, $fileDst, $srcDimensions, $radius, $backgroundColor
   )
   {
      $backgroundColor = array
      (
         'r' => UtilsString::padNumberWithZeros($backgroundColor['r'], 2),
         'g' => UtilsString::padNumberWithZeros($backgroundColor['g'], 2),
         'b' => UtilsString::padNumberWithZeros($backgroundColor['b'], 2)
      );

      // For each row in destination image...
      for ($rowNo = 0; $rowNo < $radius * 2; ++$rowNo)
      {
         // For each col in destination image...
         for ($colNo = 0; $colNo < $radius * 2; ++$colNo)
         {
            $srcCoordinates = self::getSourceCoordinatesFromDestCoordinates
            (
               $rowNo, $colNo, $srcDimensions, $radius
            );

            $color =
            (
               ($srcCoordinates === null)? $backgroundColor: self::getColorFromFileRandomAccess
               (
                  $fileSrc, $srcCoordinates['rowNo'], $srcCoordinates['colNo'], $srcDimensions
               )
            );

            fwrite($fileDst, $color['r']);
            fwrite($fileDst, $color['g']);
            fwrite($fileDst, $color['b']);
         }
      }
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function getSourceCoordinatesFromDestCoordinates
   (
      $dstR, $dstC, $srcDimensions, $maxRadius
   )
   {
      UtilsValidator::checkArray($srcDimensions, array('width' => 'int', 'height' => 'int'));

      $rDstOrigin = $dstR - $maxRadius;
      $cDstOrigin = $dstC - $maxRadius;
      $radius     = sqrt(pow($rDstOrigin, 2) + pow($cDstOrigin, 2));

      if ($radius >= $maxRadius)
      {
         return null;
      }

      $angle = self::PI + atan2($rDstOrigin, $cDstOrigin);

      // Rotate 30 degrees so that images have symmetry about a vertical axis.
      $angle += self::PI / 6;

      if ($angle >= 2 * self::PI)
      {
         $angle -= 2 * self::PI;
      }

      $rowNo = floor(($radius /  $maxRadius   ) * $srcDimensions['height']);
      $colNo = floor(($angle  / (2 * self::PI)) * $srcDimensions['width' ]);

      return array('rowNo' => $rowNo, 'colNo' => $colNo);
   }

   /*
    *
    */
   private static function getColorFromFileRandomAccess($file, $rowNo, $colNo, $srcDimensions)
   {
      $offset = 9 * ($rowNo * $srcDimensions['width'] + $colNo);

      fseek($file, $offset, SEEK_SET);

      $r = fread($file, 3);
      $g = fread($file, 3);
      $b = fread($file, 3);

      return array('r' => $r, 'g' => $g, 'b' => $b);
   }

   // Class constants. /////////////////////////////////////////////////////////////////////////

   const PI = 3.14159265358979323846;
}

/*******************************************END*OF*FILE********************************************/
?>
