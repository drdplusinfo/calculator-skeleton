<?php
declare(strict_types=1);

namespace DrdPlus\CalculatorSkeleton\Web;

use Granam\Strict\Object\StrictObject;
use Granam\WebContentBuilder\Web\BodyInterface;

class IssuesBody extends StrictObject implements BodyInterface
{
    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return <<<HTML
<div class="row">
  <div class="col">
    <div class="issues">
      <a href="https://rpgforum.cz/forum/viewtopic.php?f=238&t=14870">
        Máš nápad 😀? Vidíš chybu 😱?️ Sem s tím!
        <img alt="RPG forum icon" src="/images/generic/skeleton/rules-rpgforum-ico.png">
      </a>
    </div>
  </div>
</div>
HTML;
    }

}