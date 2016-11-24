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
    color: #000;
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
  <a href="<?= route('index') ?>" style="margin-right: 1rem;">&larr;</a>
  <?= $commit->message ?>
  <span class="chip" style="vertical-align: middle;"><?= $commit->hash ?></span>
</h3>


<div class="fixed-action-btn horizontal">
  <a class="btn-floating btn-large red">
    <i class="large material-icons">voicemail</i>
  </a>
  <ul>

    <li><a class="btn-floating red" href="<?= route('retry', ['id' => $commit->id]) ?>" title="retry"><i class="material-icons">replay</i></a></li>
  </ul>
</div>

<div class="log">
  <? if (empty($lines)) { ?>
    Build in progress
  <? } ?>
  <? foreach ($lines as $line) { ?>
    <div class="log-message level-<?= $line->level_name ?>">
      <?= nl2br($line->message) ?>
    </div>
  <? } ?>
</div>
