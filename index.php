<?php
/**************************************************************************************************\
*
* vim: ts=3 sw=3 et wrap co=100 go -=b
*
* Filename: "index.php"
*
* Project: Programming Tools - Spectrum Generator.
*
* Purpose: The main file for the project.
*
* Author: Tom McDonnell 2009-10-23.
*
\**************************************************************************************************/

// Includes. ///////////////////////////////////////////////////////////////////////////////////////

require_once dirname(__FILE__) . '/SpectrumGenerator.php';
require_once dirname(__FILE__) . '/converter_html/SpectrumRowsHtmlConverter.php';
require_once dirname(__FILE__) . '/mapper_circle/SpectrumRowsToCircleMapper.php';
require_once dirname(__FILE__) . '/configurations_rectangular.php';

// Settings. ///////////////////////////////////////////////////////////////////////////////////////

error_reporting(-1);

// Globally executed code. /////////////////////////////////////////////////////////////////////////

try
{
   $requestedId = (array_key_exists('id', $_GET))? $_GET['id']: '0';

   $n_configurations = count($configurations);

   $index = ($requestedId == 'random')? rand(0, $n_configurations - 1): $requestedId;

   if (!array_key_exists($index, $configurations)) {throw new Exception('Index out of range.');}

   $phpSelf     = $_SERVER['PHP_SELF'];
   $prevHrefStr = ($index ==                     0)? '': "href='$phpSelf?id=" . ($index - 1) . "'";
   $nextHrefStr = ($index == $n_configurations - 1)? '': "href='$phpSelf?id=" . ($index + 1) . "'";
   $randHrefStr =                                        "href='$phpSelf?id=random'";

   $filesJs  = array();
   $filesCss = array('style.css');
}
catch (Exception $e)
{
   echo $e->getMessage();
   exit(0);
}

// Functions. //////////////////////////////////////////////////////////////////////////////////////

/*
 *
 */
function createConfigurationGetString($dimensions, $cellDimensions)
{
   $getSubstrings = array
   (
      "imageWidth={$dimensions['width']}"   ,
      "imageHeight={$dimensions['height']}" ,
      "cellWidth={$cellDimensions['width']}",
      "cellHeight={$cellDimensions['height']}"
   );

   return implode('&', $getSubstrings);
}

// HTML code. //////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC
 "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
 <head>
<?php
 $unixTime = time();
 foreach ($filesJs  as $file) {echo "  <script src='$file?$unixTime'></script>\n"        ;}
 foreach ($filesCss as $file) {echo "  <link rel='stylesheet' href='$file?$unixTime'/>\n";}
?>
  <title>tomcdonnell.net - Color Spectrum Art</title>
 </head>
 <body>
  <a class='backLink' href='../../../index.php'>Back to tomcdonnell.net</a>
  <h1>Color Spectrum Art</h1>
  <a class='navLink' <?php echo $prevHrefStr; ?>>Prev</a>
  <a class='navLink' <?php echo $randHrefStr; ?>>Random</a>
  <a class='navLink' <?php echo $nextHrefStr; ?>>Next</a>
<?php
   $cellDimensions = $cellDimensionsByConfigurationIndex[$index];
   $configuration  = $configurations[$index];
   $file           = fopen('spectrum.txt', 'wb');

   if ($file === false)
   {
      echo "Could not open file.\n";
      exit(0);
   }

   $dimensions = SpectrumGenerator::writeSpectrumRowsToFile($configuration, $file);

   fclose($file);

   $fileSrc = fopen('spectrum.txt'        , 'rb');
   $fileDst = fopen('spectrumCircular.txt', 'wb');
   $radius  = 250;
   SpectrumRowsToCircleMapper::mapFromFileToFile
   (
      $fileSrc, $fileDst, $dimensions, $radius,
      (
         ($configuration['dual'])?
         array('r' => 0  , 'g' => 0  , 'b' => 0  ):
         array('r' => 255, 'g' => 255, 'b' => 255)
      )
   );
   fclose($fileSrc);
   fclose($fileDst);

   $cellDimensions = $cellDimensionsByConfigurationIndex[$index];
   $getStrR        = createConfigurationGetString($dimensions, $cellDimensions);
   $getStrC        = createConfigurationGetString
   (
      array('height' => 2 * $radius, 'width' => 2 * $radius), array('height' => 1, 'width' => 1)
   );

   $imageUrlRect = "converter_png/spectrum_image_png.php?$getStrR&filename=../spectrum.txt";
   $imageUrlCirc = "converter_png/spectrum_image_png.php?$getStrC&filename=../spectrumCircular.txt";
   $imageAltMsg  =
   (
      "The image could not be displayed.  An error occurred while the image was being generated."
   );
?>
  <div><img src='<?php echo $imageUrlRect; ?>' alt='<?php echo $imageAltMsg; ?>'/></div>
  <div><img src='<?php echo $imageUrlCirc; ?>' alt='<?php echo $imageAltMsg; ?>'/></div>
<?php
?>
 </body>
</html>
<?php
/*******************************************END*OF*FILE********************************************/
?>
