<form class="block delete" action="/" method="post" onsubmit="return window.confirm('Opravdu smazat včetně historie?')">
  <label>
    <input type="submit" value="Smazat" name="<?= \DrdPlus\Configurator\Skeleton\Controller::DELETE_HISTORY ?>" class="manual">
    <span class="hint">(včetně dlouhodobé paměti)</span>
  </label>
</form>
