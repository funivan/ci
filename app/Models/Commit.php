<?php

  namespace App\Models;

  use Illuminate\Database\Eloquent\Model;

  /**
   * @property int $id
   * @property int $status
   * @property string $hash
   * @property string $branch
   * @property string $author_email
   * @property integer $start_time
   * @property integer $message
   * @property integer $end_time
   */
  class Commit extends Model {

    const STATUS_PENDING = 1;

    const STATUS_IN_PROGRESS = 2;

    const STATUS_OK = 3;

    const STATUS_FAILURE = 4;

    /**
     * @inheritDoc
     */
    protected $table = 'commit';


    /**
     * @return string
     */
    public function getLogFilePath() {
      return config('ci.logs') . '/' . $this->id . '-' . $this->hash . '-check.log';
    }


    /**
     * @inheritdoc
     */
    public function delete() {
      $file = $this->getLogFilePath();
      if (is_file($file)) {
        unlink($file);
      }
      return parent::delete();
    }


    /**
     * @return string
     * @throws \Exception
     */
    public function getStatusText() {
      $map = [
        1 => 'pending',
        2 => 'in progress',
        3 => 'ok',
        4 => 'failure',
      ];

      if (!isset($map[$this->status])) {
        throw new \Exception('Cant detect status name:' . $this->status);
      }

      return $map[$this->status];
    }


    /**
     * @return int|string
     */
    public function getFormattedStartTime() {
      if (empty($this->start_time)) {
        return 0;
      }
      return \DateTime::createFromFormat('U', $this->start_time)->format('Y-m-d H:i');
    }


    public function getAuthorGravatarUrl(int $size = 16) : string {
      return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->author_email))) . "?s=" . $size;
    }

  }
