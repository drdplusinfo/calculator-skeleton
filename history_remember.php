<div class="block remember">
  <label><input type="checkbox" name="<?= \DrdPlus\Configurator\Skeleton\Controller::REMEMBER_CURRENT ?>" value="1"
                <?php /** @var \DrdPlus\Configurator\Skeleton\Controller $controller */
                if ($controller->shouldRemember()) { ?>checked="checked"<?php } ?>>
    Pamatovat <span class="hint">(i při zavření prohlížeče)</span></label>
</div>