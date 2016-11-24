<?
  /**
   * @var \League\Plates\Template\Template $this
   * @var \App\Models\Commit[] $commits
   */
  $this->layout('layout/html', ['title' => 'Build list']);
?>

<style>
  .commits-table .info {
    padding-left: 1.5rem;
    width: 29%;
  }

  .commits-table .info img {
    float: left;
    margin-top: 3px;
  }

  .commits-table .info .author {
    margin-left: 53px;
  }

  .commits-table .info .date {
    margin-left: 53px;
    font-size: 90%;
    color: #777;
  }

  .commits-table .branch {
    text-align: right;
    width: 7%;
    min-width: 7rem;
  }

  .commits-table .branch span, .commits-table .profile span {
    display: inline-block;
    color: black;
    font-weight: normal;
    font-size: 85%;
    padding: 0.2rem 0.55rem 0.3rem 0.55rem;
    background: #acf;
    margin-right: 1rem;
    border-radius: 3px;
    white-space: nowrap;
    max-width: 80%;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .commits-table .branch span.master {
    background: #9d9;
  }

  .commits-table .profile span {
    background: #d0e9c6;
  }

  .commits-table .commit .id {
    font-size: 90%;
    color: #777;
  }

  .commits-table .commit .message {
    font-weight: bold;
    max-width: 550px;
  }

  .commits-table .status {
    width: 8rem;
    text-align: center;
  }

  .commits-table .log {
    width: 8rem;
    text-align: center;
  }
</style>


<? if (count($commits) > 0) { ?>
  <table class="commits-table highlight striped bordered">
    <thead>
    <tr>
      <th class="info">Authored</th>
      <th class="branch"></th>
      <th class="profile"></th>
      <th class="commit">Commit</th>
      <th class="status">Status</th>
      <th class="log">Log</th>
    </tr>
    </thead>
    <? foreach ($commits as $commit) { ?>
      <tr>
        <td class="info">
          <img src="<?= $commit->getAuthorGravatarUrl(36) ?>" alt="" class="z-depth-1">
          <div class="author truncate"><?= $commit->author_email ?></div>
          <div class="date truncate"><?= $commit->getFormattedStartTime() ?></div>
        </td>
        <td class="branch">
          <span class="<?= $commit->branch === 'master' ? 'master' : '' ?>"
                title="<?= $commit->branch ?>">
            <?= $commit->branch ?>
          </span>
        </td>
        <td class="profile">
          <span><?= $commit->profile ?></span>
        </td>
        <td class="commit" title="<?= htmlspecialchars(preg_replace("!\n.*$!", '', trim($commit->message))) ?>">
          <div class="message truncate">
            <?= htmlspecialchars(preg_replace("!\n.*$!", '', trim($commit->message))) ?>
          </div>
          <div class="id">
            <?= substr($commit->hash, 0, 8) ?>
          </div>
        </td>
        <td title="<?= $commit->getStatusText(); ?>" class="status">
          <? if ($commit->status == \App\Models\Commit::STATUS_OK) { ?>
            <i style="color:green" class="small material-icons">thumb_up</i>
          <? } elseif ($commit->status == \App\Models\Commit::STATUS_IN_PROGRESS) { ?>
            <i style="color:blue" class="small material-icons">play_for_work</i>
          <? } elseif ($commit->status == \App\Models\Commit::STATUS_PENDING) { ?>
            <i style="color:gray" class="small material-icons">input</i>
          <? } elseif ($commit->status == \App\Models\Commit::STATUS_FAILURE) { ?>
            <i style="color:red" class="small material-icons">thumb_down</i>
          <? } ?>
        </td>
        <td class="log" style="width: 9rem;">
          <a href="<?= route('viewCommitInfo', ['id' => $commit->id]) ?>" class="btn">log</a>
        </td>
      </tr>
    <? } ?>
  </table>
<? } else { ?>
  <h4>No commits</h4>
<? } ?>
