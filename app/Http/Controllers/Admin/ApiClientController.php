<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class ApiClientController extends Controller { public function index(){return view('admin.integrations.api_clients.index',['clients'=>ApiClient::latest()->paginate(20)]);} public function create(){return view('admin.integrations.api_clients.create');} public function store(Request $r){$data=$r->validate(['name'=>'required','code'=>'required|unique:api_clients,code','description'=>'nullable','permissions'=>'nullable|array','rate_limit_per_minute'=>'required|integer|min:1']); $token='qe_'.Str::random(48); $client=ApiClient::create($data+['token_hash'=>Hash::make($token),'created_by'=>$r->user()->id,'is_active'=>true]); AuditLogger::record('created','api_clients','API Client creado'); return view('admin.integrations.api_clients.show',['client'=>$client,'plainToken'=>$token]);} public function show(ApiClient $apiClient){return view('admin.integrations.api_clients.show',['client'=>$apiClient,'plainToken'=>null]);} public function revoke(ApiClient $apiClient){$apiClient->update(['is_active'=>false]); AuditLogger::record('revoked','api_clients','API Client revocado'); return back()->with('success','API Client revocado.');} }
