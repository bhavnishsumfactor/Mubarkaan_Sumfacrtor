<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Writer;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class LanguagesExport implements FromArray, ShouldAutoSize, WithEvents
{
    use RegistersEventListeners;

    private $data;
    private $exportExcelPassword;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->exportExcelPassword = '!sdf#g2345#';
    }

    public function array(): array
    {
        return $this->data;
    }
    
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class  => function (BeforeWriting $event) {
                Writer::macro('setPassword', function (Writer $writer, string $password) {
                    $writer->getDelegate()->getActiveSheet()->getProtection()->setSheet(true);
                    $writer->getDelegate()->getActiveSheet()->getProtection()->setPassword($password);
                    $writer->getDelegate()->getActiveSheet()->getStyle('B2:F1000')->getProtection()
                    ->setLocked(Protection::PROTECTION_UNPROTECTED);
                });
                $event->writer->setPassword($this->exportExcelPassword);
            }
        ];
    }
}
