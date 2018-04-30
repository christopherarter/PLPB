<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.bundle.min.js" integrity="sha384-lZmvU/TzxoIQIOD9yQDEpvxp6wEU32Fy0ckUgOH4EIlMOCdR823rg4+3gWRwnX1M" crossorigin="anonymous"></script>
<?php
require_once(__DIR__ . '/vendor/autoload.php');
require_once('PageBuilder.php');

// $pageBuilder = new PageBuilder('components');

// echo "<pre>";
// var_dump($pageBuilder->getRequiredKeys('01-homepage.twig'));
// echo "</pre>";


$loader = new Twig_Loader_Filesystem('components');
$twig = new Twig_Environment($loader);

$path = 'components/homepage.twig';

$template = $twig->load('homepage.twig');
$jsonString = fread(fopen('variables.json', 'r'), filesize('variables.json'));
if($jsonString){

    $jsonVariables = json_decode($jsonString, true);
    if(is_array($jsonVariables)){

        echo $template->render($jsonVariables);
    }

}

// if( $jsonString ){

//     if( is_array( json_decode($jsonString))){
//         $template->render( json_decode($jsonString) );
//     }
// }


$templateAsString = fread(fopen($path, 'r'), filesize('components/homepage.twig'));
$variables = preg_match_all( '/\{{.+?(?=\||\}})/', $templateAsString, $matches );
$cleanedMatches = [];
foreach ($matches as $match){
    $match = str_replace('{{ ', '', $match);
    $match = str_replace('{{', '', $match);
    $match = str_replace(' ', '', $match);
    // array_push($cleanedMatches, $match);
    $cleanedMatches += $match;
}
   
?>
<form method="POST">
  <div class="form-group col-md-6 col">
      <?php 

      foreach($cleanedMatches as $field) {
          ?>

        <label for="exampleFormControlFile1"><?php echo ucfirst($field); ?></label>
        <input name="variables[<?php echo $field; ?>]" type="text" class="form-control" id="exampleFormControlFile1">

          <?php 
      } ?>
      <button type = "submit" class="btn btn-default">Submit</button>
</div>

</form>


<?php 

if( isset( $_POST['variables'] ) ){

    $json = json_encode($_POST['variables']); 

    $file = fopen('variables.json', 'w');
    fwrite($file, $json);
    fclose($file);
}
// $template = $twig->load('homepage.twig');
// // echo "<pre>";
// // var_dump($template->getBlockNames());
// // echo "</pre>";

// $newValues = [];
// foreach($template->getBlockNames() as $block ){
//     $newValues[$block] = $values[$block];
// }

// // echo "<pre>";
// // var_dump($template->render('homepage.twig', $newValues));
// // echo "</pre>";

// echo $template->render($newValues);


