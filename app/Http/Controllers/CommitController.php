<?php

  namespace App\Http\Controllers;

  use App\Http\Requests;
  use App\Models\Commit;

  class CommitController extends Controller {

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


    public function showBuildList() {
      $commits = Commit::all();
      return view('list', ['commits' => $commits]);
    }


    public function viewLog($id) {
      $commit = Commit::where('id', '=', $id)->get()->get(0);
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
      return view('log', ['lines' => $lines]);
    }

  }
