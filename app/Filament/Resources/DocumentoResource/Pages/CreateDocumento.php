<?php

namespace App\Filament\Resources\DocumentoResource\Pages;

use App\Filament\Resources\DocumentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use ZipArchive;
use App\Models\Archivo;

class CreateDocumento extends CreateRecord
{
    protected static string $resource = DocumentoResource::class;
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Procesar el archivo Excel
        $excelPath = $data['excel']->store('excels');
        $spreadsheet = IOFactory::load(Storage::path($excelPath));
        $sheet = $spreadsheet->getActiveSheet();

        // Leer datos del archivo Excel y crear registros de Documento
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }

            $documento = Documento::create([
                'nrodocumento' => $cells[0],
                'nombre' => $cells[1],
                'placa' => $cells[2],
            ]);

            // Procesar el archivo Zip
            $zipPath = $data['zip']->store('zips');
            $zip = new ZipArchive;
            if ($zip->open(Storage::path($zipPath)) === TRUE) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (pathinfo($filename, PATHINFO_EXTENSION) === 'pdf') {
                        $fileContent = $zip->getFromIndex($i);
                        $filePath = 'pdfs/' . $filename;
                        Storage::put($filePath, $fileContent);

                        // Guardar la información del archivo en la base de datos
                        Archivo::create([
                            'nombre' => $filename,
                            'ruta' => $filePath,
                            'documento_id' => $documento->id,
                        ]);
                    }
                }
                $zip->close();
            }
        }

        return $documento;
    }
}
