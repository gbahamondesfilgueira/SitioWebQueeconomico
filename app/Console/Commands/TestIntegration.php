<?php
namespace App\Console\Commands;
use App\Models\Integration;
use App\Services\Integrations\IntegrationService;
use Illuminate\Console\Command;
class TestIntegration extends Command { protected $signature='integrations:test {integration}'; protected $description='Prueba conexión mock/sandbox de una integración.'; public function handle(IntegrationService $service): int { $integration=Integration::query()->where('code',$this->argument('integration'))->firstOrFail(); $result=$service->testConnection($integration); $this->info($result['message'] ?? 'OK'); return ($result['success'] ?? false) ? self::SUCCESS : self::FAILURE; } }
