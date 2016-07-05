<?

  namespace App\Ci\Commands;

  /**
   *
   */
  class MailCommand extends BaseCommand {

    /**
     * @var  string
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $body;


    public function __construct(string $to, string $subject, string $body) {
      $this->to = $to;
      $this->subject = $subject;
      $this->body = $body;
    }


    public function execute() {

      $this->getRunConfig()->getLogger()->debug('Send email ' . $this->subject . ' to ' . $this->to);

      $result = mail($this->to, $this->subject, $this->body);
      if ($result === false) {
        throw new \Exception('Can not send email:' . $this->subject);
      }

    }

  }
