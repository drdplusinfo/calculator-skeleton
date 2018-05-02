<?php
namespace DrdPlus\Calculators\Rest;

use DrdPlus\Calculator\Skeleton\Controller;

include_once __DIR__ . '/vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', '1');

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/generic/vendor/bootstrap.4.0.0/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/generic/vendor/bootstrap.4.0.0/bootstrap-grid.min.css" rel="stylesheet" type="text/css">
    <link href="css/generic/vendor/bootstrap.4.0.0/bootstrap-reboot.min.css" rel="stylesheet" type="text/css">
    <link href="css/generic/graphics.css" rel="stylesheet" type="text/css">
    <link href="css/generic/skeleton.css" rel="stylesheet" type="text/css">
    <link href="css/generic/issues.css" rel="stylesheet" type="text/css">
    <noscript>
      <link href="css/generic/no_script.css" rel="stylesheet" type="text/css">
    </noscript>
  </head>
  <body class="container">
    <div class="background"></div>
      <?php include __DIR__ . '/history_deletion.php';
      $controller = \Mockery::mock(Controller::class);
      $controller->shouldReceive('shouldRemember')
          ->andReturn(false);
      $controller->shouldReceive('getCurrentValuesAsHiddenInputs')
          ->andReturn('');
      include __DIR__ . '/history_remember.php' ?>
    <div>
      <form method="get" action="">
        <label>nic
          <input type="checkbox" value="1" name="nic" <?php if (!empty($_GET['nic'])) { ?>checked="checked"<?php } ?> ></label>
      </form>
    </div>
      <?php
      /** @noinspection PhpUnusedLocalVariableInspection */
      $sourceCodeUrl = 'https://github.com/jaroslavtyc/drdplus-calculator-skeleton';
      include __DIR__ . '/issues.php' ?>
    <script type="text/javascript" src="js/generic/skeleton.js"></script>
  </body>
</html>
