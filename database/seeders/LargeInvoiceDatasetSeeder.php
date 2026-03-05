<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LargeInvoiceDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $invoiceTargetCount = 10000;
        $paymentTargetCount = 5000;
        $partialTargetCount = 100;
        $invoiceChunkSize = 500;

        $user = User::query()->first();
        if (!$user) {
            $user = User::query()->create([
                'name' => 'Seed User',
                'email' => 'seed.user@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        $faker = fake();
        $now = now();

        $existingClientCount = DB::table('clients')->where('user_id', $user->id)->count();
        $targetClientCount = 300;
        if ($existingClientCount < $targetClientCount) {
            $newClients = [];
            for ($i = $existingClientCount + 1; $i <= $targetClientCount; $i++) {
                $newClients[] = [
                    'user_id' => $user->id,
                    'name' => $faker->name(),
                    'email' => 'client' . $i . '@example.com',
                    'phone' => $faker->phoneNumber(),
                    'address' => $faker->address(),
                    'company' => $faker->company(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            foreach (array_chunk($newClients, 500) as $chunk) {
                DB::table('clients')->insert($chunk);
            }
        }

        $existingProductCount = DB::table('products')->where('user_id', $user->id)->count();
        $targetProductCount = 120;
        if ($existingProductCount < $targetProductCount) {
            $newProducts = [];
            for ($i = $existingProductCount + 1; $i <= $targetProductCount; $i++) {
                $newProducts[] = [
                    'user_id' => $user->id,
                    'name' => $faker->words(2, true),
                    'description' => $faker->sentence(),
                    'price' => $faker->randomFloat(2, 25, 2000),
                    'unit' => $faker->randomElement(['pcs', 'hrs', 'kg', 'pkg']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            foreach (array_chunk($newProducts, 500) as $chunk) {
                DB::table('products')->insert($chunk);
            }
        }

        $clientIds = DB::table('clients')->where('user_id', $user->id)->pluck('id')->all();
        $products = DB::table('products')
            ->where('user_id', $user->id)
            ->select('id', 'name', 'description', 'price')
            ->get()
            ->all();

        if (empty($clientIds) || empty($products)) {
            $this->command?->error('Cannot seed invoices: clients or products are missing.');
            return;
        }

        $lastInvoiceNumber = Invoice::query()->latest('id')->value('invoice_number');
        $nextInvoiceCounter = $lastInvoiceNumber
            ? ((int) substr($lastInvoiceNumber, 4)) + 1
            : 1;

        $createdInvoiceMeta = [];
        $taxRates = [0, 5, 8, 10, 12];

        for ($offset = 0; $offset < $invoiceTargetCount; $offset += $invoiceChunkSize) {
            $batchSize = min($invoiceChunkSize, $invoiceTargetCount - $offset);
            $invoiceRows = [];
            $itemBlueprints = [];
            $invoiceNumbers = [];

            for ($i = 0; $i < $batchSize; $i++) {
                $invoiceNumber = 'INV-' . str_pad((string) $nextInvoiceCounter, 8, '0', STR_PAD_LEFT);
                $nextInvoiceCounter++;
                $invoiceNumbers[] = $invoiceNumber;

                $issueDate = Carbon::instance($faker->dateTimeBetween('-18 months', 'now'))->startOfDay();
                $dueDate = (clone $issueDate)->addDays(random_int(7, 45));
                $taxRate = (float) $faker->randomElement($taxRates);

                $statusRoll = random_int(1, 100);
                $status = $statusRoll <= 15 ? 'draft' : ($statusRoll <= 75 ? 'sent' : 'overdue');

                $lineCount = random_int(1, 5);
                $items = [];
                $subtotal = 0.0;

                for ($j = 0; $j < $lineCount; $j++) {
                    $product = $products[array_rand($products)];
                    $quantity = (float) random_int(1, 8);
                    $unitPrice = (float) $product->price;
                    $lineTotal = round($unitPrice * $quantity, 2);
                    $subtotal += $lineTotal;

                    $items[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description ?: $faker->sentence(),
                        'price' => $unitPrice,
                        'quantity' => $quantity,
                        'total' => $lineTotal,
                    ];
                }

                $subtotal = round($subtotal, 2);
                $taxAmount = round($subtotal * ($taxRate / 100), 2);
                $total = round($subtotal + $taxAmount, 2);

                $invoiceRows[] = [
                    'user_id' => $user->id,
                    'client_id' => $clientIds[array_rand($clientIds)],
                    'invoice_number' => $invoiceNumber,
                    'status' => $status,
                    'issue_date' => $issueDate->toDateString(),
                    'due_date' => $dueDate->toDateString(),
                    'subtotal' => $subtotal,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'notes' => $faker->optional(0.35)->sentence(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $itemBlueprints[$invoiceNumber] = $items;
            }

            DB::table('invoices')->insert($invoiceRows);

            $insertedInvoices = DB::table('invoices')
                ->whereIn('invoice_number', $invoiceNumbers)
                ->select('id', 'invoice_number', 'total', 'issue_date')
                ->get();

            $itemRows = [];
            foreach ($insertedInvoices as $invoice) {
                $createdInvoiceMeta[(int) $invoice->id] = [
                    'total' => (float) $invoice->total,
                    'issue_date' => (string) $invoice->issue_date,
                ];

                foreach ($itemBlueprints[$invoice->invoice_number] as $item) {
                    $itemRows[] = [
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['total'],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach (array_chunk($itemRows, 2000) as $itemChunk) {
                DB::table('invoice_items')->insert($itemChunk);
            }
        }

        $invoiceIds = array_keys($createdInvoiceMeta);
        shuffle($invoiceIds);
        $paymentInvoiceIds = array_slice($invoiceIds, 0, min($paymentTargetCount, count($invoiceIds)));
        $partialInvoiceIds = array_slice($paymentInvoiceIds, 0, min($partialTargetCount, count($paymentInvoiceIds)));
        $partialInvoiceLookup = array_fill_keys($partialInvoiceIds, true);

        $paymentMethods = ['cash', 'bank_transfer', 'check', 'credit_card', 'online'];
        $paymentRows = [];
        $paidInvoiceIds = [];

        foreach ($paymentInvoiceIds as $invoiceId) {
            $meta = $createdInvoiceMeta[$invoiceId];
            $total = max($meta['total'], 0.01);

            $isPartial = isset($partialInvoiceLookup[$invoiceId]);
            $amount = $isPartial
                ? round($total * ($faker->randomFloat(2, 0.2, 0.8)), 2)
                : $total;
            $amount = min(max($amount, 0.01), $total);

            $issueDate = Carbon::parse($meta['issue_date'])->startOfDay();
            $paymentDate = Carbon::instance($faker->dateTimeBetween($issueDate, 'now'))->toDateString();

            $paymentRows[] = [
                'user_id' => $user->id,
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_date' => $paymentDate,
                'method' => $paymentMethods[array_rand($paymentMethods)],
                'reference' => 'PAY-' . strtoupper(Str::random(10)),
                'notes' => $isPartial ? 'Partial payment (seeded dataset).' : $faker->optional(0.2)->sentence(),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (!$isPartial) {
                $paidInvoiceIds[] = $invoiceId;
            }
        }

        foreach (array_chunk($paymentRows, 1000) as $paymentChunk) {
            DB::table('payments')->insert($paymentChunk);
        }

        if (!empty($paidInvoiceIds)) {
            DB::table('invoices')->whereIn('id', $paidInvoiceIds)->update([
                'status' => 'paid',
                'updated_at' => $now,
            ]);
        }

        if (!empty($partialInvoiceIds)) {
            DB::table('invoices')->whereIn('id', $partialInvoiceIds)->update([
                'status' => 'partial',
                'updated_at' => $now,
            ]);
        }

        $this->command?->info('Seeded large dataset: 10,000 invoices, ~5,000 payments, and 100 partial-payment invoices.');
    }
}
