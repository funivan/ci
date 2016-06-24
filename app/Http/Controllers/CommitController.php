<?php

  namespace App\Http\Controllers;

  use App\Http\Requests;
  use App\Models\Commit;

  /**
   *
   * @package App\Http\Controllers
   */
  class CommitController extends Controller {

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function addBuild() {
      $build = new Commit();
      $build->branch = request('branch');
      $build->status = Commit::STATUS_PENDING;
      $build->hash = request('hash');
      $build->author = request('author');
      $build->start_time = time();
      $build->end_time = 0;
      $build->save();

      return response(['id' => $build->id]);
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


      $lines = [];
      /** @var Commit $commit */
      $resource = fopen($commit->getLogFilePath(), 'r');
      while (!feof($resource)) {
        $line = fgets($resource);
        $data = json_decode($line);
        if (empty($data)) {
          continue;
        }
        $lines[] = $data;
      }

      fclose($resource);
      return view(
        'log',
        [
          'commit' => $commit,
          'lines' => $lines
        ]
      );
    }

  }
