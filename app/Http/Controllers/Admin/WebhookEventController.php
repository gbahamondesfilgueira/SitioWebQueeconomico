<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\WebhookEvent;
class WebhookEventController extends Controller { public function index(){return view('admin.integrations.webhooks.index',['events'=>WebhookEvent::with('integration')->latest('created_at')->paginate(30)]);} public function show(WebhookEvent $webhookEvent){return view('admin.integrations.webhooks.show',['event'=>$webhookEvent->load('integration')]);} }
