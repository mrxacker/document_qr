<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use setasign\Fpdi\Fpdi;

class DocumentController extends Controller
{

    public function index()
    {
        $documents = Document::with('documentFiles')->get();
        return view('documents.index', compact('documents'));
    }


    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        // Validate and create the Document
        $request->validate([
            'sender_name' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'sender_department_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'files.*' => 'required|file|mimes:pdf',
        ]);

        $document = Document::create([
            'sender_name' => $request->sender_name,
            'receiver_name' => $request->receiver_name,
            'sender_department_name' => $request->sender_department_name,
            'phone' => $request->phone,
        ]);

        // Generate QR Code data
        $qrData = "Sender: {$document->sender_name}, Receiver: {$document->receiver_name}, Phone: {$document->phone}";
        $qrCodePath = storage_path("app/public/qrcodes/{$document->id}.png");

        // Generate and save the QR code image
        QrCode::format('png')->size(200)->generate($qrData, $qrCodePath);

        // Process each uploaded file
        foreach ($request->file('files') as $file) {
            // Save the original PDF
            $originalPath = $file->store('document_files', 'public');
            $documentFile = $document->documentFiles()->create(['file_path' => $originalPath]);

            // Modify the PDF to include the QR code, and save it back to the original path
            $pdfPath = storage_path("app/public/{$originalPath}");

            $fpdi = new Fpdi();
            $pageCount = $fpdi->setSourceFile($pdfPath);

            // Iterate over each page and add the QR code to the first page only
            for ($page = 1; $page <= $pageCount; $page++) {
                $template = $fpdi->importPage($page);
                $size = $fpdi->getTemplateSize($template);

                $fpdi->AddPage();
                $fpdi->useTemplate($template, 0, 0, $size['width'], $size['height']);

                // Add the QR code image to the first page
                if ($page == 1) {

                    $qrCodeSize = 10; // QR code width and height in mm
                    $xPosition = $size['width'] - $qrCodeSize - 10; // 10 mm margin from the right
                    $yPosition = $size['height'] - $qrCodeSize - 10; // 10 mm margin from the bottom

                    $fpdi->Image($qrCodePath, $xPosition, $yPosition, $qrCodeSize, $qrCodeSize);
                }
            }

            // Overwrite the original PDF with the modified version
            $fpdi->Output($pdfPath, 'F');
        }

        return redirect('/')->with('success', 'Document and QR code added successfully!');
    }
}
