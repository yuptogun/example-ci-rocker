<?php

if (is_array($site['menu'])) :

?>
<nav class="sticky-top navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('/') ?>">
      <?= $site['name']['short'] ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <?php if ($site['menu']['left']) : ?><ul class="navbar-nav mr-auto">
<?php

foreach ($site['menu']['left'] as $menu => $item) {

  $isActive = current_url() == site_url($item['route']);
  $hasSubmenu = !empty($item['submenu']);
  $link = $hasSubmenu ? '#' : $item['route'];

?>
        <li class="nav-item<?= $isActive ? ' active' : '' ?>">
          <a class="nav-link<?= $hasSubmenu ? ' dropdown-toggle' : '' ?>" href="<?= $link ?>"<?= $hasSubmenu ? ' id="dropdown'.$menu.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '' ?>>
            <?= $item['name'] ?><?php if ($isActive) : ?> <span class="sr-only">(current)</span><?php endif; ?>
          </a>
<?php

if ($hasSubmenu) :

?>
          <div class="dropdown-menu" aria-labelledby="dropdown<?= $menu ?>">
<?php

foreach ($item['submenu'] as $submenu => $subitem) :

?>
            <a class="dropdown-item" href="<?= site_url($subitem['route']) ?>"><?= $subitem['name'] ?></a>
<?php

endforeach;

?>
          </div>
<?php

endif;

?>
        </li>
<?php

}

?>
      </ul>
<?php endif; ?>
<?php if ($site['menu']['right']) : ?>
      <ul class="navbar-nav ml-auto">
<?php foreach ($site['menu']['right'] as $menu => $item) {
  $isActive = current_url() == site_url($item['route']); ?>
        <li class="nav-item<?= $isActive ? ' active' : '' ?>">
          <a class="nav-link" href="<?= site_url($item['route']) ?>">
            <?= $item['name'] ?>
          </a>
        </li>
<?php } ?>
      </ul>
<?php endif; ?>
      <!-- <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="검색" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">검색</button>
      </form> -->
    </div>
  </div>
</nav>
<?php endif; ?>