<?

  namespace App\Ci\Commands;

  /**
   *
   */
  class MailCommand extends BaseCommand {

    private $to = null;


    private $subject = null;

    private $body = null;


    public function execute() {

      $this->getRunConfig()->getLogger()->debug(
        sprintf('Send email %s to %s', $this->subject, $this->to)
      );

      $result = mail($this->to, $this->subject, $this->body);
      if ($result === false) {
        throw new \Exception('Can not send email:' . $this->subject);
      }

    }

  }
