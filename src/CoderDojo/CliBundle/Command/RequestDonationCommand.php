<?php

namespace CoderDojo\CliBundle\Command;

use CoderDojo\WebsiteBundle\Command\ExpireCocRequestCommand;
use CoderDojo\WebsiteBundle\Entity\Club100;
use CoderDojo\WebsiteBundle\Entity\CocRequest;
use CoderDojo\WebsiteBundle\Entity\Donation;
use CoderDojo\WebsiteBundle\Service\NextDonationFinder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RequestDonationCommand extends ContainerAwareCommand
{
    /**
     * CronJobs
     *
     * 30 18 1 4,10 * => Semi-yearly
     * 30 18 1 *\3 *  => Quarterly
     * 30 18 1 6 *    => Yearly
     */
    protected function configure()
    {
        $this
            ->setName('donations:request')
            ->setDescription('Requests donations from our users')
            ->addArgument('interval', InputArgument::REQUIRED)
            ->addOption('dry-run', 'd')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->getContainer()->get('doctrine')->getRepository(Club100::class);
        $donationRepository = $this->getContainer()->get('doctrine')->getRepository(Donation::class);
        $interval = $input->getArgument('interval');
        $members = $repository->findBy(['confirmed' => true, 'interval' => $interval]);

        $io = new SymfonyStyle($input, $output);
        $io->section('Starting with '.count($members).' member');

        /** @var Club100 $member */
        foreach($members as $member) {
            $donation = new Donation($member);

            $existing = $donationRepository->findOneBy(
                [
                    'member' => $member,
                    'year' => $donation->getYear(),
                    'quarter' => $donation->getQuarter(),
                ]
            );

            if (null === $existing) {
                $io->writeln(sprintf('Member %s %s has no donation yet, creating new one', $member->getFirstName(), $member->getLastName()));
                $this->getContainer()->get('doctrine')->getManager()->persist($donation);
                $this->getContainer()->get('doctrine')->getManager()->flush();
                $this->getContainer()->get('doctrine')->getManager()->refresh($donation);
            } else {
                $io->writeln(sprintf('Member %s %s already has a donation - %s', $member->getFirstName(), $member->getLastName(), $donation->getUuid()));
                $donation = $existing;
            }

            if ($donation->isPaid()) {
                $io->writeln(sprintf('Member %s %s has already paid for donation %s on %s', $member->getFirstName(), $member->getLastName(), $donation->getUuid(), $donation->getPaidAt()->format(DATE_ATOM)));
                $io->newLine(2);
                continue;
            }

            /**
             * Send email to dojo contact address
             */
            $message = \Swift_Message::newInstance()
                ->setSubject('Jouw Club van 100 donatie')
                ->setFrom('contact@coderdojo.nl', 'CoderDojo Nederland')
                ->setTo($member->getEmail())
                ->setBcc('website+club100@coderdojo.nl')
                ->setContentType('text/html')
                ->setBody(
                    $this->getContainer()->get('templating')->render(':Pages:ClubVan100/Email/payment_request.html.twig', ['member' => $member, 'donation'=>$donation])
                );

            $this->getContainer()->get('mailer')->send($message);

            $io->writeln(sprintf('Member %s %s has received a request for donation %s', $member->getFirstName(), $member->getLastName(), $donation->getUuid()));
            $io->newLine(2);
        }

        $io->success('Done all members for this period!');
    }
}
