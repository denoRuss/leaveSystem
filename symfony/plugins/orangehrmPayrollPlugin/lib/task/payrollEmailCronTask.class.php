<?php

/**
 * Cron job  to send pending emails in email pool
 */
class payrollEmailCronTask extends sfBaseTask {

    protected $logger;
    protected $fromEmail;

    protected function configure() {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'orangehrm'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
                // add your own options here
        ));

        $this->namespace = '';
        $this->name = 'process-email-pool';
        $this->briefDescription = 'Send Emails in Pool';
        $this->detailedDescription = <<<EOF
The [process-email-pool |INFO] task does things.
Call it with:

  [php symfony process-email-pool|INFO]
EOF;
    }

    public function getLogger() {
        if (empty($this->logger)) {
            $this->logger = Logger::getLogger('core.email');
        }
        return $this->logger;
    }

    protected function execute($arguments = array(), $options = array()) {

        // create context
        $context = sfContext::createInstance($this->configuration);

        // set date format
        $configService = new ConfigService();
        $datePattern = $configService->getAdminLocalizationDefaultDateFormat();
        $context->getUser()->setDateFormat($datePattern);

        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

        $emailPoolService = new DkEmailPoolService();
        $mailService = new EmailService();

        $unsentEmails = $emailPoolService->searchEmailPoolByStatus(array(EmailPool::STATUS_PENDING,EmailPool::STATUS_PROCESSING));

        $this->getLogger()->info('Started sending pool emails');


        foreach ($unsentEmails as $email) {
            $email->setStatus(EmailPool::STATUS_PROCESSING);
        }
        $unsentEmails->save();

        foreach ($unsentEmails as $email) {

            $this->getLogger()->info('Sending email to:' . $email->getToAddress() . ', cc:' . $email->getCcAddress());

            $to = trim($email->getToAddress());
            $cc = trim($email->getCcAddress());

            if (!empty($cc)) {
                $ccAddresses = explode(',', $cc);
            } else {
                $ccAddresses = array();
            }
            $mailService->setMessageCc($ccAddresses);

            $toAddresses = array();
            if (!empty($to)) {
                $toAddresses = explode(',', $to);
            }


            if(count($toAddresses)==0)
                continue;


            $mailService->setMessageTo($toAddresses);

            $mailService->setMessageSubject($email->getSubject());

            $contType = $email->getContentType();
            if (is_null($contType)) {
                $contType = EmailPool::CONTENT_TYPE_PLAIN;
            }
            $mailService->setMessageBody($email->getBody());
//            $mailService->setMessageContentType($contType);


            $result = $mailService->sendEmail();

            if ($result) {
                // set as email sent success
                $email->setStatus(EmailPool::STATUS_SENT);
            } else {
                $this->getLogger()->warn('Error Sending mail to -> ' . $email->getToAddress());
            }

            try{
                $emailPoolService->saveEmail($email);
            }
            catch (Exception $e){
                $this->getLogger()->info('Error occured during updating email in pool : '+$email->getId());
            }

        }

        $this->getLogger()->info('Ended processing queued emails');
    }

}
