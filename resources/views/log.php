<style type="text/css">
  .level_emergency {
    color: red;
  }
</style>
<? foreach ($lines as $line) { ?>
  <div class="level_<?= $line->level_name ?>">
    <?= nl2br($line->message) ?>
  </div>
<? } ?>