<!DOCTYPE html>
<html>
  <head>
    <title>List</title>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <? /** @var \App\Models\Commit[] $commits */ ?>
      <? if (count($commits) > 0) { ?>
        <table class="highlight">
          <? /** @var \App\Models\Commit[] $commits */ ?>
          <? foreach ($commits as $commit) { ?>
            <tr>
              <td style="cursor: pointer;" title="<?= $commit->hash; ?>">
                <?= $commit->id; ?>
              </td>
              <td>
                <?= $commit->author; ?>
              </td>
              <td>
                <?= $commit->getFormattedStartTime(); ?>
              </td>
              <td style="cursor: pointer;" title="<?= $commit->getStatusText(); ?>">
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
              <td>
                <a href="/view/<?= $commit->id ?>">log</a>
              </td>
            </tr>
          <? } ?>

        </table>
      <? } else { ?>
        <h4>No commits</h4>
      <? } ?>
    </div>

  </body>
</html>
