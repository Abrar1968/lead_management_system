<?php

namespace Database\Seeders;

use App\Models\Conversion;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\LeadContact;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesPersons = User::where('role', 'sales_person')->pluck('id')->toArray();
        $sources = ['WhatsApp', 'Messenger', 'Website'];
        $services = ['Website', 'Software', 'CRM', 'Marketing'];
        $priorities = ['High', 'Medium', 'Low'];
        $responseStatuses = ['Interested', '50%', 'Yes', 'Call Later', 'No Response', 'No', 'Phone off'];

        $clientNames = [
            'Mohammad Ali', 'Rafiq Islam', 'Jabbar Khan', 'Shakil Ahmed', 'Nasir Uddin',
            'Kamrul Hasan', 'Mizanur Rahman', 'Sohel Rana', 'Imran Hossain', 'Tariq Aziz',
            'Farhan Kabir', 'Zahir Raihan', 'Belal Hossain', 'Mamun Rashid', 'Shafiq Alam',
        ];

        $companies = [
            'Tech Solutions BD', 'Digital Marketing Pro', 'E-Commerce Hub', 'Software House',
            'Web Design Studio', 'IT Consultant Ltd', 'Business Solutions', 'Data Systems',
            null, null, null, // Some leads without company
        ];

        $leadCount = 1;

        // Create leads for the last 30 days
        for ($daysAgo = 30; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::today()->subDays($daysAgo);
            $leadsToday = rand(2, 6); // 2-6 leads per day

            for ($i = 0; $i < $leadsToday; $i++) {
                $leadNumber = sprintf('LEAD-%s-%03d', $date->format('Ymd'), $i + 1);
                $assignedTo = $salesPersons[array_rand($salesPersons)];

                $lead = Lead::create([
                    'lead_number' => $leadNumber,
                    'source' => $sources[array_rand($sources)],
                    'client_name' => $clientNames[array_rand($clientNames)],
                    'phone_number' => '017'.rand(10000000, 99999999),
                    'email' => rand(0, 1) ? 'client'.$leadCount.'@example.com' : null,
                    'company_name' => $companies[array_rand($companies)],
                    'service_interested' => $services[array_rand($services)],
                    'lead_date' => $date->format('Y-m-d'),
                    'lead_time' => sprintf('%02d:%02d:00', rand(9, 18), rand(0, 59)),
                    'is_repeat_lead' => false,
                    'previous_lead_ids' => null,
                    'priority' => $priorities[array_rand($priorities)],
                    'assigned_to' => $assignedTo,
                ]);

                // Add 1-3 lead contacts for older leads
                if ($daysAgo > 2) {
                    $contactCount = rand(1, 3);
                    for ($c = 0; $c < $contactCount; $c++) {
                        LeadContact::create([
                            'lead_id' => $lead->id,
                            'daily_call_made' => true,
                            'call_date' => $date->copy()->addDays($c)->format('Y-m-d'),
                            'call_time' => sprintf('%02d:%02d:00', rand(9, 18), rand(0, 59)),
                            'caller_id' => $assignedTo,
                            'response_status' => $responseStatuses[array_rand($responseStatuses)],
                            'notes' => rand(0, 1) ? 'Discussed requirements and pricing.' : null,
                        ]);
                    }
                }

                // Add follow-ups for some leads
                if (rand(0, 1) && $daysAgo > 5) {
                    FollowUp::create([
                        'lead_id' => $lead->id,
                        'follow_up_date' => Carbon::today()->addDays(rand(1, 7))->format('Y-m-d'),
                        'follow_up_time' => sprintf('%02d:%02d:00', rand(9, 18), rand(0, 59)),
                        'notes' => 'Follow up on proposal discussion.',
                        'status' => 'Pending',
                        'created_by' => $assignedTo,
                    ]);
                }

                // Add meetings for some leads
                if (rand(0, 100) < 20 && $daysAgo > 7) {
                    Meeting::create([
                        'lead_id' => $lead->id,
                        'meeting_date' => $date->copy()->addDays(rand(1, 5))->format('Y-m-d'),
                        'meeting_time' => sprintf('%02d:%02d:00', rand(10, 17), 0),
                        'meeting_type' => rand(0, 1) ? 'Online' : 'Physical',
                        'outcome' => rand(0, 1) ? ['Positive', 'Neutral', 'Negative'][rand(0, 2)] : null,
                        'notes' => 'Project discussion meeting.',
                    ]);
                }

                // Convert some older leads
                if (rand(0, 100) < 15 && $daysAgo > 10) {
                    $user = User::find($assignedTo);
                    $dealValue = rand(20, 100) * 1000; // 20k - 100k

                    $commissionAmount = $user->commission_type === 'fixed'
                        ? $user->default_commission_rate
                        : ($dealValue * $user->default_commission_rate / 100);

                    Conversion::create([
                        'lead_id' => $lead->id,
                        'converted_by' => $assignedTo,
                        'conversion_date' => $date->copy()->addDays(rand(3, 7))->format('Y-m-d'),
                        'deal_value' => $dealValue,
                        'commission_rate_used' => $user->default_commission_rate,
                        'commission_type_used' => $user->commission_type,
                        'commission_amount' => $commissionAmount,
                        'package_plan' => ['Basic', 'Standard', 'Premium'][rand(0, 2)],
                        'advance_paid' => rand(0, 1),
                        'payment_method' => ['Bank Transfer', 'bKash', 'Cash'][rand(0, 2)],
                        'signing_date' => $date->copy()->addDays(rand(2, 5))->format('Y-m-d'),
                        'project_status' => ['In Progress', 'Delivered', 'On Hold'][rand(0, 2)],
                        'commission_paid' => rand(0, 1),
                    ]);
                }

                $leadCount++;
            }
        }
    }
}
