<?php

namespace HerCat\ZhipinScanLogin\Console;

use PHPQRCode\QRcode as QrCodeConsole;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class QrCode extends Console
{
    public function show($text)
    {
        $output = new ConsoleOutput();
        self::initQrcodeStyle($output);

        $pxMap[0] = $this->getWhiteBlock();
        $pxMap[1] = '<blackc>  </blackc>';

        $text = QrCodeConsole::text($text);

        $length = strlen($text[0]);

        $this->printLine($output, $length);

        foreach ($text as $line) {
            $output->write($pxMap[0]);
            for ($i = 0; $i < $length; $i++) {
                $type = substr($line, $i, 1);
                $output->write($pxMap[$type]);
            }
            $output->writeln($pxMap[0]);
        }

        $this->printLine($output, $length);
        $output->write("\n");
    }

    public function printLine(OutputInterface $output, $length = 10)
    {
        $block = $this->getWhiteBlock();

        for ($i = 0; $i < $length + 2; $i++) {
            $output->write($block);
        }

        $output->write("\n");
    }

    public function getWhiteBlock()
    {
        return Console::isWin() ? '<whitec>mm</whitec>' : '<whitec>  </whitec>';
    }

    /**
     * Init qrCode style.
     *
     * @param OutputInterface $output
     */
    private static function initQrcodeStyle(OutputInterface $output)
    {
        $style = new OutputFormatterStyle('black', 'black', ['bold']);
        $output->getFormatter()->setStyle('blackc', $style);
        $style = new OutputFormatterStyle('white', 'white', ['bold']);
        $output->getFormatter()->setStyle('whitec', $style);
    }
}