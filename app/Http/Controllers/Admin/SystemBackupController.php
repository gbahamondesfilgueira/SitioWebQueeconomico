<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemBackup;
use App\Services\BackupService;
use Illuminate\Http\Request;

class SystemBackupController extends Controller
{
    public function index(BackupService $backups)
    {
        return view('admin.system.backups.index', ['backups' => $backups->listBackups()]);
    }

    public function store(Request $request, BackupService $backups)
    {
        $validated = $request->validate(['backup_type' => ['required', 'in:database,files,full']]);
        $backup = match ($validated['backup_type']) {
            'files' => $backups->createFilesBackup($request->user()->id),
            'full' => $backups->createFullBackup($request->user()->id),
            default => $backups->createDatabaseBackup($request->user()->id),
        };

        return redirect()->route('admin.system.backups.show', $backup)->with('success', 'Proceso de backup registrado.');
    }

    public function show(SystemBackup $backup, BackupService $backups)
    {
        return view('admin.system.backups.show', ['backup' => $backup, 'isValid' => $backups->validateBackupFile($backup)]);
    }
}
