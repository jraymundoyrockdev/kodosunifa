<?php

namespace Kodosunifa;

use Symfony\Component\Console\Style\SymfonyStyle;

trait SniffHelper
{
    /**
     * @var array
     */
    private $applicationTitle = [
        '   __          __        _  _',
        '  /   _  _| _ (_ __  o _|__|_ _  __',
        '  \__(_)(_|(/___)| | |  |  | (/_ | ',
        ''
    ];

    /**
     * @param SymfonyStyle $io
     * @param array $details
     *
     * @return void
     */
    public function setSnifferInformation(SymfonyStyle $io, $details = [])
    {
        $io->text($this->applicationTitle);

        foreach ($details as $detailName => $detail) {
            $io->text($detailName . $detail);
        }

        $io->text('');
        $io->text('SNIFFING...');
    }

    /**
     * @param SymfonyStyle $io
     * @param string $messageType
     * @param string $message
     */
    public function handleMessages(SymfonyStyle $io, $messageType, $message)
    {
        $io->{$messageType}($message);
    }
}
