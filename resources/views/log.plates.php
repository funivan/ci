<?
  /**
   * @var \League\Plates\Template\Template $this
   * @var \App\Models\Commit $commit
   */
  $this->layout('layout/html', ['title' => 'Build log - ' . $commit->message . ' - ' . $commit->hash]);
?>

<style type="text/css">
  .level-DEBUG {
    color: #999;
  }

  .level-INFO {
    color: #BLACK;
  }

  .level-EMERGENCY {
    color: red;
    font-weight: bold;
  }

  .log {
    padding: 1rem;
    border: 1px solid #eee;
    margin-bottom: 1.5rem;
    margin-top: 2rem;
  }

  .log-message {
    font-family: Menlo, Monaco, Consolas, monospace;
    font-size: 9pt;
  }
</style>

<h3 class="crumbs">
  <a href="/" style="margin-right: 1rem;">&larr;</a>
  <?= $commit->message ?>
  <span class="chip" style="vertical-align: middle;"><?= $commit->hash ?></span>
</h3>

<div class="log">
  <? foreach ($lines as $line) { ?>
    <div class="log-message level-<?= $line->level_name ?>">
      <?= nl2br($line->message) ?>
    </div>
  <? } ?>
</div>
