<?php

namespace App\Console\Commands;

use App\Services\TermRelativeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkReciprocalTermRelatives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terms:link-reciprocal-relatives {--dry-run : Report links without updating rows}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link existing term_relative rows to their reciprocal rows.';

    public function __construct(private readonly TermRelativeService $termRelativeService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $stats = [
            'linked' => 0,
            'missing' => 0,
            'ambiguous' => 0,
            'mismatched' => 0,
        ];

        DB::table('term_relative')
            ->whereNull('reciprocal_id')
            ->orderBy('id')
            ->chunkById(500, function ($rows) use (&$stats) {
                foreach ($rows as $row) {
                    $current = DB::table('term_relative')->where('id', $row->id)->first();

                    if (! $current || $current->reciprocal_id) {
                        continue;
                    }

                    $expectedType = $this->termRelativeService->reciprocalRelativeType($current->type);
                    $candidates = DB::table('term_relative')
                        ->where('term_id', $current->relative_id)
                        ->where('relative_id', $current->term_id)
                        ->where('type', $expectedType)
                        ->where('id', '!=', $current->id)
                        ->whereNull('reciprocal_id')
                        ->get();

                    if ($candidates->count() === 1) {
                        $candidate = $candidates->first();

                        if (! $this->option('dry-run')) {
                            DB::table('term_relative')
                                ->where('id', $current->id)
                                ->update([
                                    'reciprocal_id' => $candidate->id,
                                    'updated_at' => now(),
                                ]);

                            DB::table('term_relative')
                                ->where('id', $candidate->id)
                                ->update([
                                    'reciprocal_id' => $current->id,
                                    'updated_at' => now(),
                                ]);
                        }

                        $stats['linked']++;
//                        $this->line("Linked term_relative {$current->id} <-> {$candidate->id}");

                        continue;
                    }

                    if ($candidates->count() > 1) {
                        $stats['ambiguous']++;
                        $this->logAmbiguity('ambiguous', $current, [
                            'expected_type' => $expectedType,
                            'candidate_ids' => $candidates->pluck('id')->all(),
                        ]);

                        continue;
                    }

                    $reverseRows = DB::table('term_relative')
                        ->where('term_id', $current->relative_id)
                        ->where('relative_id', $current->term_id)
                        ->where('id', '!=', $current->id)
                        ->get();

                    if ($reverseRows->isNotEmpty()) {
                        $stats['mismatched']++;
                        $this->logAmbiguity('mismatched_type', $current, [
                            'expected_type' => $expectedType,
                            'reverse_rows' => $reverseRows
                                ->map(fn ($reverseRow) => [
                                    'id' => $reverseRow->id,
                                    'type' => $reverseRow->type,
                                    'reciprocal_id' => $reverseRow->reciprocal_id,
                                ])
                                ->all(),
                        ]);

                        continue;
                    }

                    $stats['missing']++;
                    $this->logAmbiguity('missing', $current, [
                        'expected_type' => $expectedType,
                    ]);
                }
            });

        $this->info(sprintf(
            'Finished linking term relatives. Linked: %d. Missing: %d. Ambiguous: %d. Mismatched: %d.',
            $stats['linked'],
            $stats['missing'],
            $stats['ambiguous'],
            $stats['mismatched'],
        ));

        return self::SUCCESS;
    }

    private function logAmbiguity(string $reason, object $row, array $context): void
    {
        $payload = [
            'reason' => $reason,
            'pivot_id' => $row->id,
            'term_id' => $row->term_id,
            'relative_id' => $row->relative_id,
            'type' => $row->type,
            ...$context,
        ];

        Log::warning('Unable to link reciprocal term relative.', $payload);
        $this->warn('Unable to link term_relative '.$row->id.': '.$reason);
    }
}
