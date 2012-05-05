<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go-=b
*
* Filename: "SpectrumGenerator.php"
*
* Project: Art - Spectrum Generator.
*
* Purpose: Display a configurable color spectrum pattern as an HTML table.
*
* Author: Tom McDonnell 2009-11-03.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/lib_tom/php/utils/UtilsValidator.php';
require_once dirname(__FILE__) . '/lib_tom/php/utils/UtilsString.php';

// Class definition. ///////////////////////////////////////////////////////////////////////////////

/*
 *
 */
class SpectrumGenerator
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
   public static function writeSpectrumRowsToFile($configuration, $file)
   {
      UtilsValidator::checkArray
      (
         $configuration, array
         (
            'includeReflection' => 'bool'        ,
            'filter'            => 'nullOrString',
            'dual'              => 'bool'        ,
            'n_subrows'         => 'positiveInt'
         )
      );
      extract($configuration);

      $colorRows = self::filterColorRowsColumns(self::generateFullColorRows(), $filter);
      $n_cols    = count($colorRows[0]);

      switch ($n_cols)
      {
       //case 12:
       //  self::swapColumns($colorRows, 4, array(array(0, 11), array(3, 4), array(7, 8)));
       //  break;
       //case 18:
       //  self::swapColumns($colorRows, 4, array(array(0, 17), array(5, 6), array(11, 12)));
       //  break;
       default:
         // Do nothing.
      }

      if ($dual)
      {
         $colorRows = self::createColorRowsDual($colorRows);
      }

      return self::writeSpectrumRowsToFileFromColorRows
      (
         $colorRows, $n_subrows, $includeReflection, $file
      );
   }

   // Private functions. ////////////////////////////////////////////////////////////////////////

   /*
    *
    */
   private static function debugEchoColorRowsAsText($colorRows)
   {
      echo "<br/>\n";
      foreach ($colorRows as $colorRow)
      {
         foreach ($colorRow as $color)
         {
            echo "$color ";
         }

         echo "<br/>\n";
      }
   }

   /*
    * Swap pairs of columns of $colorRows, for rows from $startRow downwards.
    */
   private static function swapColumns(&$colorRows, $startRowIndex, $columnIndexPairs)
   {
      foreach ($colorRows as $rowIndex => &$row)
      {
         if ($rowIndex < $startRowIndex)
         {
            continue;
         }

         foreach ($columnIndexPairs as $pair)
         {
            $tempValue     = $row[$pair[0]];
            $row[$pair[0]] = $row[$pair[1]];
            $row[$pair[1]] = $tempValue;
         }
      }
   }

   /*
    *
    */
   private static function createColorRowsDual($colorRows)
   {
      $colorRowsDual = array();

      for ($r = 0, $n_rows = count($colorRows); $r < $n_rows; ++$r)
      {
         $colorRow     = $colorRows[$r];
         $colorRowDual = array();

         for ($c = 0, $n_cols = count($colorRow); $c < $n_cols; ++$c)
         {
            $color     = $colorRow[$c];
            $colorDual = '';

            for ($i = 0; $i < 3; ++$i)
            {
               switch ($color[$i])
               {
                case '0': $colorDual .= '1'; break;
                case '1': $colorDual .= '0'; break;
                default : throw new Exception("Unexpected color value '{$color[$i]}'.");
               }
            }

            $colorRowDual[] = $colorDual;
         }

         $colorRowsDual[] = $colorRowDual;
      }

      return $colorRowsDual;
   }

   /**
    * @param filter {String}
    *    A string of binary digits such as '100', '010', '001'.
    *    The string may be prefixed with a 'not' operator '!' to filter everything that
    *    would not have been filtered without the 'not' operator.  Eg. '!100', '!010', '!001'.
    *
    * @return
    *    The globally defined colorRows array, minus columns whose
    *    value in the last row does not match the supplied filter.
    */
   private static function filterColorRowsColumns($colorRows, $filter)
   {
      $filteredRows = array();
      $n_rows       = count($colorRows);
      $colorRowLast = $colorRows[$n_rows - 1];

      for ($r = 0; $r < $n_rows; ++$r)
      {
         $colorRow    = $colorRows[$r];
         $n_cols      = count($colorRow);
         $filteredRow = array();

         for ($c = 0; $c < $n_cols; ++$c)
         {
            switch ($filter === null)
            {
             case true :
               if ($colorRowLast[$c] !== '000') {$filteredRow[] = $colorRow[$c];}
               break;
             case false:
               if ($filter[0] == '!')
               {
                  if ($colorRowLast[$c] !== '000' && substr($filter, 1) !== $colorRowLast[$c])
                  {
                     $filteredRow[] = $colorRow[$c];
                  }
               }
               else
               {
                  if ($filter === $colorRowLast[$c]) {$filteredRow[] = $colorRow[$c];}
               }
               break;
            }
         }

         $filteredRows[] = $filteredRow;
      }

      return $filteredRows;
   }

   /*
    *
    */
   private static function writeSpectrumRowsToFileFromColorRows
   (
      $colorRows, $n_subrowsPerRow, $includeReflection, $file
   )
   {
      $n_subRows = (count($colorRows) - 1) * $n_subrowsPerRow + 1;
      $n_cols    = count($colorRows[0]);

      for ($rowNo = 0; $n_rows = count($colorRows), $rowNo < $n_rows; ++$rowNo)
      {
         $colorRow = $colorRows[$rowNo];

         switch ($rowNo == $n_rows - 1)
         {
          case true:
            $colorRowNext = null;
            $n_subrows    = 1;
            break;
          case false:
            $colorRowNext = $colorRows[$rowNo + 1];
            $n_subrows    = $n_subrowsPerRow;
            break;
         }

         for ($subrowNo = 0; $subrowNo < $n_subrows; ++$subrowNo)
         {
            self::writeSpectrumRowToFile($colorRow, $colorRowNext, $subrowNo, $n_subrows, $file);
         }
      }

      if (!$includeReflection)
      {
         return array('width' => $n_cols, 'height' => $n_subRows);
      }

      for ($rowNo = count($colorRows) - 2; $rowNo >= 0; --$rowNo)
      {
         $colorRow = $colorRows[$rowNo];

         switch ($rowNo == $n_rows - 1)
         {
          case true:
            $colorRowNext = null;
            $n_subrows    = 1;
            break;
          case false:
            $colorRowNext = $colorRows[$rowNo + 1];
            $n_subrows    = $n_subrowsPerRow;
            break;
         }

         for ($subrowNo = $n_subrows - 1; $subrowNo >= 0; --$subrowNo)
         {
            self::writeSpectrumRowToFile($colorRow, $colorRowNext, $subrowNo, $n_subrows, $file);
         }
      }

      return array('width' => $n_cols, 'height' => 2 * $n_subRows);
   }

   /*
    *
    */
   private static function writeSpectrumRowToFile
   (
      $colorRow, $colorRowNext, $subrowNo, $n_subrows, $file
   )
   {
      for ($colNo = 0; $n_cols = count($colorRow), $colNo < $n_cols; ++$colNo)
      {
         $colorStr = $colorRow[$colNo];
         $r        = (int)$colorStr[0];
         $g        = (int)$colorStr[1];
         $b        = (int)$colorStr[2];

         if ($colorRowNext !== null)
         {
            $colorStrNext = $colorRowNext[$colNo];
            $rNext        = (int)$colorStrNext[0];
            $gNext        = (int)$colorStrNext[1];
            $bNext        = (int)$colorStrNext[2];

            $r = $r + ($rNext - $r) * ($subrowNo / $n_subrows);
            $g = $g + ($gNext - $g) * ($subrowNo / $n_subrows);
            $b = $b + ($bNext - $b) * ($subrowNo / $n_subrows);
         }

         $r = UtilsString::padNumberWithZeros((int)($r * 255), 2);
         $g = UtilsString::padNumberWithZeros((int)($g * 255), 2);
         $b = UtilsString::padNumberWithZeros((int)($b * 255), 2);

         fwrite($file, $r);
         fwrite($file, $g);
         fwrite($file, $b);
      }
   }

   /*
    *
    */
   private static function generateFullColorRows()
   {
      $rootNode = array('color' => '000', 'links' => array('l' => null, 'c' => null, 'r' => null));

      self::addNodesToNodeRecursively($rootNode, array('000'), 'c');

      $colorRows = array();

      for ($rowNo = 0; $rowNo < 7; ++$rowNo)
      {
         $colorRows[$rowNo] = array();
      }

      foreach (self::$colorCols as $colNo => $colorCol)
      {
         foreach ($colorCol as $rowNo => $color)
         {
            if ($color === null)
            {
               $color = '000';
            }

            $colorRows[$rowNo][$colNo] = $color;
         }
      }

      return $colorRows;
   }

   /*
    *
    */
   private static function addNodesToNodeRecursively(&$node, $colorsInNodesAbove, $directionOrder)
   {
      if (count($colorsInNodesAbove) == 8)
      {
         self::$colorCols[] = $colorsInNodesAbove;
         return;
      }

      switch ($directionOrder)
      {
       case 'l': $directions = array('r', 'l', 'c'); break;
       case 'c': $directions = array('l', 'c', 'r'); break;
       case 'r': $directions = array('c', 'r', 'l'); break;
       default: throw new Exception('Unknown direction order.');
      }

      foreach ($directions as $direction)
      {
         if ($node['links'][$direction] === null)
         {
            $newColor =
            (
               (in_array(null, $colorsInNodesAbove))? null:
               self::getColorByTogglingOneOfRgbInString($node['color'], $direction)
            );

            if (in_array($newColor, $colorsInNodesAbove))
            {
               $newColor = null;
            }

            $node['links'][$direction] = array
            (
               'color' => $newColor, 'links' => array('l' => null, 'c' => null, 'r' => null)
            );

            self::addNodesToNodeRecursively
            (
               $node['links'][$direction],
               array_merge($colorsInNodesAbove, array($newColor)), $direction
            );
         }
      }
   }

   /*
    *
    */
   private static function getColorByTogglingOneOfRgbInString($string, $direction)
   {
      if (strlen($string) != 3)
      {
         throw new Exception('Unexpected string length.');
      }

      switch ($direction)
      {
       case 'l': $index = 0; break;
       case 'c': $index = 1; break;
       case 'r': $index = 2; break;
       default: throw new Exception('Unknown direction.');
      }

      switch ($string[$index])
      {
       case '0': $newValue = '1'; break;
       case '1': $newValue = '0'; break;
       default: throw new Exception('Unknown value.');
      }

      $string[$index] = $newValue;

      return $string;
   }

   // Private variables. ////////////////////////////////////////////////////////////////////////

   private static $colorCols = array();
}

/*******************************************END*OF*FILE********************************************/
?>
