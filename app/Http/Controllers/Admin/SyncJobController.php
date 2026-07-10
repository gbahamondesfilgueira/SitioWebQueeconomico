<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SyncJob;
class SyncJobController extends Controller { public function index(){return view('admin.integrations.sync_jobs.index',['jobs'=>SyncJob::with('integration')->latest()->paginate(30)]);} public function show(SyncJob $syncJob){return view('admin.integrations.sync_jobs.show',['job'=>$syncJob->load('integration')]);} }
