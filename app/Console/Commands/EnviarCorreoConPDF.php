<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Documento;
use App\Models\Archivo;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class EnviarCorreoConPDF extends Command
{
    protected $signature = 'enviar:correo';

    protected $description = 'Enviar correo con PDF adjunto';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $documentos = Documento::all();

        foreach ($documentos as $documento) {
            // Crear el PDF
            $pdf = Pdf::loadView('pdf.plantilla', compact('documento'));

            $pdfPath = storage_path('app/public/') . $documento->placa . '.pdf';
            $pdf->save($pdfPath);

            // Adjuntar PDFs del ZIP
            $zip = new ZipArchive;
            $mergePdf = new \Clegginabox\PDFMerger\PDFMerger;

            $mergePdf->addPDF($pdfPath, 'all');

            $archivos = Archivo::where('documento_id', $documento->id)->get();
            foreach ($archivos as $archivo) {
                $mergePdf->addPDF(Storage::path($archivo->ruta), 'all');
            }

            $mergedPdfPath = storage_path('app/public/') . $documento->placa . '_merged.pdf';
            $mergePdf->merge('file', $mergedPdfPath);

            // Enviar el correo
            Mail::send('emails.plantilla', compact('documento'), function ($message) use ($mergedPdfPath, $documento) {
                $message->to('correo@ejemplo.com')
                        ->subject('Datos del Documento ' . $documento->nrodocumento)
                        ->attach($mergedPdfPath);
            });

            // Eliminar archivos temporales
            unlink($pdfPath);
            unlink($mergedPdfPath);
        }
    }
}
