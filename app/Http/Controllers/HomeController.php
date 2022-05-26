<?php

namespace App\Http\Controllers;

use DirectoryIterator;
use File;
use Illuminate\Http\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Spatie\PdfToImage\Exceptions\PdfDoesNotExist;
use Spatie\PdfToImage\Pdf;
use ZipArchive;

class HomeController extends Controller
{
    public function index() {
        return view('index');
    }

    public function pdfToImage(Request $request) {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10048',
        ]);

        $file = $request->file('pdf_file');
        $name = time();
        $fileName = $name.'.'.$file->extension();
        $filePath = public_path('uploads/'.$name);
        $file->move($filePath, $fileName);

        try {
            $pdf = new Pdf($filePath.'/'.$fileName);
            $pageLimit = $pdf->getNumberOfPages() > 10 ? 10: $pdf->getNumberOfPages();

            if($pdf->getNumberOfPages() > 1) {
                //using if to account for possible count of 0, rather than just relying on the loop
                for ($i = 1; $i <= $pageLimit; $i++) {
                    $imageName = $filePath.'/'.$i . ".jpg";
                    $pdf->setPage($i)->saveImage($imageName);
                }
            } else {
                $imageName = $filePath.'/' . "1.png";
                $pdf->saveImage($imageName);
            }

            $archive = $this->compressFiles($name);

            $this->deleteDir($filePath);

            return response()->download(
                $archive,
                $name.'.zip',
                array('Content-Type: application/zip','Content-Length: '. filesize($archive))
            )->deleteFileAfterSend(true);
        } catch (PdfDoesNotExist $e) {
            echo $e->getMessage();
        }
    }

    private function compressFiles($name) {
        $zip = new ZipArchive();
        $fileName = $name . '.zip';
        $filePath = public_path('downloads/' . $fileName);

        if ($zip->open($filePath, ZipArchive::CREATE) === TRUE) {
            $rootPath = public_path('uploads/'.$name);
            self::folderToZip($rootPath, $zip, strlen("downloads/$fileName"));
            $zip->close();
        }

        return $filePath;
    }

    private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
        $handle = opendir($folder);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                $ext = pathinfo($f, PATHINFO_EXTENSION);

                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);
                //echo $localPath; exit;
                if (is_file($filePath) && $ext != 'pdf') {
                    $zipFile->addFile($filePath, $f);
                } elseif (is_dir($filePath)) {
                    // Add sub-directory.
                    //$zipFile->addEmptyDir($localPath);
                    //self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    private function deleteDir($path) {
        $handle = opendir($path);
        while (false !== $f = readdir($handle)) {
            $filePath = "$path/$f";
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
        closedir($handle);
        rmdir($path);
    }
}
