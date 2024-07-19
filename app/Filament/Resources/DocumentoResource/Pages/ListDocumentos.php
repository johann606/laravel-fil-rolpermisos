<?php
namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;
use App\Models\Documento;
use App\Models\Archivo;

class ListDocumentos extends ListRecords
{
    protected static string $resource = DocumentoResource::class;

    protected function getActions(): array
    {
        return [
            Action::make('create')
                ->label('Crear Documento')
                ->url($this->getResource()::getUrl('create')),

            Action::make('uploadExcel')
                ->label('Cargar Excel')
                ->form([
                    Forms\Components\FileUpload::make('excel')
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']),
                ])
                ->action(function (array $data) {
                    $excelFile = $data['excel'];
                    $excelPath = $excelFile->store('excels');

                    $spreadsheet = IOFactory::load(Storage::path($excelPath));
                    $sheet = $spreadsheet->getActiveSheet();

                    foreach ($sheet->getRowIterator() as $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);

                        $cells = [];
                        foreach ($cellIterator as $cell) {
                            $cells[] = $cell->getValue();
                        }

                        Documento::create([
                            'nrodocumento' => $cells[0],
                            'nombre' => $cells[1],
                            'placa' => $cells[2],
                        ]);
                    }
                }),

            Action::make('uploadZip')
                ->label('Cargar Zip')
                ->form([
                    Forms\Components\FileUpload::make('zip')
                        ->required()
                        ->acceptedFileTypes(['application/zip']),
                ])
                ->action(function (array $data) {
                    $zipFile = $data['zip'];
                    $zipPath = $zipFile->store('zips');

                    $zip = new ZipArchive;
                    if ($zip->open(Storage::path($zipPath)) === TRUE) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            if (pathinfo($filename, PATHINFO_EXTENSION) === 'pdf') {
                                $fileContent = $zip->getFromIndex($i);
                                $filePath = 'pdfs/' . $filename;
                                Storage::put($filePath, $fileContent);

                                Archivo::create([
                                    'nombre' => $filename,
                                    'ruta' => $filePath,
                                ]);
                            }
                        }
                        $zip->close();
                    }
                }),
        ];
    }
}
