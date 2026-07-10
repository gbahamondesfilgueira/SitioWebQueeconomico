<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ExternalOrderMapping;
class ExternalOrderMappingController extends Controller { public function index(){return view('admin.integrations.order_mappings.index',['mappings'=>ExternalOrderMapping::with(['integration','order'])->latest()->paginate(30)]);} public function show(ExternalOrderMapping $externalOrderMapping){return view('admin.integrations.order_mappings.show',['mapping'=>$externalOrderMapping->load(['integration','order'])]);} }
