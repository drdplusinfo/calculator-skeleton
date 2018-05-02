<?php /** @var \DrdPlus\Calculator\Skeleton\Controller $controller */ ?>
<form method="get" action="">
    <?= $controller->getCurrentValuesAsHiddenInputs([$controller::REMEMBER_CURRENT] /* except */) ?>
  <div class="row">
    <div class="col remember">
      <label><input type="checkbox" name="<?= \DrdPlus\Calculator\Skeleton\Controller::REMEMBER_CURRENT ?>" value="1"
                    <?php /** @var \DrdPlus\Calculator\Skeleton\Controller $controller */
                    if ($controller->shouldRemember()) { ?>checked="checked"<?php } ?>>
        Pamatovat <span class="hint">(i při zavření prohlížeče)</span></label>
    </div>
  </div>
</form>
