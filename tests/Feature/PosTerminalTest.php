<?php

namespace Tests\Feature;

use App\Models\PosTerminal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosTerminalTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_terminal_factory_can_create_a_terminal(): void
    {
        $terminal = PosTerminal::factory()->create();

        $this->assertDatabaseHas('pos_terminals', [
            'id' => $terminal->id,
        ]);

        $this->assertInstanceOf(PosTerminal::class, $terminal);
    }

    public function test_pos_terminal_can_be_updated(): void
    {
        $terminal = PosTerminal::factory()->create();

        $terminal->update([
            'name' => 'Caja Principal',
        ]);

        $this->assertDatabaseHas('pos_terminals', [
            'id' => $terminal->id,
            'name' => 'Caja Principal',
        ]);
    }

    public function test_pos_terminal_can_be_soft_deleted(): void
    {
        $terminal = PosTerminal::factory()->create();

        $terminal->delete();

        $this->assertSoftDeleted('pos_terminals', [
            'id' => $terminal->id,
        ]);
    }
}