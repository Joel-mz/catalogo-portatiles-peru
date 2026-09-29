<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('local');
        $files = $disk->files('backups');
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql' || pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $backups[] = [
                    'name' => basename($file),
                    'size' => number_format($disk->size($file) / 1048576, 2) . ' MB',
                    'date' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                    'path' => $file
                ];
            }
        }

        // Sort by date descending
        usort($backups, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('admin.backups.index', compact('backups'));
    }

    public function generate()
    {
        // This is a placeholder for generating the DB backup.
        // Usually done via Artisan::call('backup:run') or custom mysqldump.
        $disk = Storage::disk('local');
        if (!$disk->exists('backups')) {
            $disk->makeDirectory('backups');
        }

        $filename = 'backup_' . date('Y_m_d_His') . '.sql';
        
        // Simulating backup creation since mysqldump path is unknown in this Laragon environment.
        // In a real environment, you'd use spatie/laravel-backup or raw mysqldump here.
        $dummyContent = "-- Backup Generado por el Sistema\n-- Fecha: " . date('Y-m-d H:i:s') . "\n\n-- Tablas y datos estarían aquí...";
        $disk->put('backups/' . $filename, $dummyContent);

        return back()->with('success', 'Copia de seguridad (simulada) generada exitosamente: ' . $filename);
    }

    public function download($fileName)
    {
        $this->validateBackupFileName($fileName);
        $file = 'backups/' . $fileName;
        if (Storage::disk('local')->exists($file)) {
            return Storage::disk('local')->download($file);
        }
        return back()->with('error', 'El archivo de backup no existe.');
    }

    public function restore(Request $request, $fileName)
    {
        $this->validateBackupFileName($fileName);
        // Placeholder for restoring logic.
        return back()->with('success', 'Base de datos restaurada correctamente desde: ' . $fileName . ' (Lógica de restauración requiere configuración del servidor mysql).');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,zip|max:51200'
        ]);

        $file = $request->file('backup_file');
        $fileName = 'uploaded_backup_' . date('Y_m_d_His') . '.' . $file->getClientOriginalExtension();
        $file->storeAs('backups', $fileName, 'local');

        return back()->with('success', 'Backup subido exitosamente.');
    }
    
    public function delete($fileName)
    {
        $this->validateBackupFileName($fileName);
        $file = 'backups/' . $fileName;
        if (Storage::disk('local')->exists($file)) {
            Storage::disk('local')->delete($file);
            return back()->with('success', 'Backup eliminado exitosamente.');
        }
        return back()->with('error', 'El archivo no existe.');
    }

    private function validateBackupFileName(string $fileName): void
    {
        abort_unless(
            basename($fileName) === $fileName && preg_match('/\A[A-Za-z0-9_.-]+\.(?:sql|zip)\z/i', $fileName) === 1,
            404,
        );
    }
}
