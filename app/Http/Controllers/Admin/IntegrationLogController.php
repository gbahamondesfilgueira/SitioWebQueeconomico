<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\IntegrationLog;
class IntegrationLogController extends Controller { public function index(){return view('admin.integrations.logs.index',['logs'=>IntegrationLog::query()->with('integration')->latest('created_at')->paginate(30)]);} public function show(IntegrationLog $integrationLog){return view('admin.integrations.logs.show',['log'=>$integrationLog->load('integration')]);} }
