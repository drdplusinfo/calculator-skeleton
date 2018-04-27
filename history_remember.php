<div class="row remember">
  <label><input type="checkbox" name="<?= \DrdPlus\Calculator\Skeleton\Controller::REMEMBER_CURRENT ?>" value="1"
                <?php /** @var \DrdPlus\Calculator\Skeleton\Controller $controller */
                if ($controller->shouldRemember()) { ?>checked="checked"<?php } ?>>
    Pamatovat <span class="hint">(i při zavření prohlížeče)</span></label>
</div>