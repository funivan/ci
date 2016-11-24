<?php

  namespace App\Http\Controllers;

  use App\Models\Commit;

  /**
   *
   * @package App\Http\Controllers
   */
  class CommitController extends Controller {

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function addCommit() {
      $commit = new Commit();
      $commit->status = Commit::STATUS_PENDING;
      $commit->hash = request('hash');
      $commit->branch = request('branch');
      $commit->profile = request('profile', '');
      $commit->start_time = time();
      $commit->end_time = 0;
      $commit->save();

      return response(['id' => $commit->id]);
    }


    public function retry(int $id) {
      $commit = Commit::query()->where('id', '=', $id)->get()->first();
      if (empty($commit)) {
        throw new \Exception('Invalid commit id: ' . $id);
      }

      /** @var Commit $commit */
      $commit->status = Commit::STATUS_PENDING;
      $commit->save();

      return redirect(route('viewCommitInfo', ['id' => $commit->id]));
    }


    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function showBuildList() {
      $commits = Commit::query()->forPage(1, 20)->orderBy('id', 'desc')->get()->all();
      return view('list', ['commits' => $commits]);
    }


    /**
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Exception
     */
    public function viewLog($id) {
      $commit = Commit::query()->where('id', '=', $id)->get()->first();
      if (empty($commit)) {
        throw new \Exception('Invalid commit id: ' . $id);
      }


      /** @var Commit $commit */
      $lines = [];
      $lines = $this->getLogLines($commit, $lines);

      return view(
        'log',
        [
          'commit' => $commit,
          'lines' => $lines,
        ]
      );
    }


    /**
     * @param $commit
     * @return array
     */
    private function getLogLines(Commit $commit) {
      $lines = [];

      $filePath = $commit->getLogFilePath();
      if (!is_file($filePath)) {
        return $lines;
      }

      $resource = fopen($filePath, 'r');
      while (!feof($resource)) {
        $line = fgets($resource);
        $data = json_decode($line);
        if (empty($data)) {
          continue;
        }
        $lines[] = $data;
      }

      fclose($resource);

      return $lines;
    }

  }
