<?php

namespace Database\Seeders;

use App\Models\TaskTemplate;
use App\Models\TaskTemplateItem;
use Illuminate\Database\Seeder;

class TaskTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'code' => 'general',
                'name' => 'General Delivery Template',
                'sort_order' => 5,
                'items' => [
                    ['title' => 'Kickoff & requirements freeze', 'estimate_minutes' => 90, 'role_hint' => 'Owner'],
                    ['title' => 'Collect assets/credentials from client', 'estimate_minutes' => 60, 'role_hint' => 'Owner'],
                    ['title' => 'Setup project tracker + milestones', 'estimate_minutes' => 45, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Create staging/demo environment', 'estimate_minutes' => 120, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Development implementation', 'estimate_minutes' => 480, 'role_hint' => 'LaravelLead'],
                    ['title' => 'QA & bugfix', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'Deploy to production', 'estimate_minutes' => 120, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Handover + documentation', 'estimate_minutes' => 90, 'role_hint' => 'Owner'],
                ],
            ],
            [
                'code' => 'wp',
                'name' => 'WordPress Website Template',
                'sort_order' => 10,
                'items' => [
                    ['title' => 'Hosting setup + WordPress install', 'estimate_minutes' => 120, 'role_hint' => 'WPLead'],
                    ['title' => 'Theme/child theme setup', 'estimate_minutes' => 120, 'role_hint' => 'WPLead'],
                    ['title' => 'Required plugins install & config', 'estimate_minutes' => 180, 'role_hint' => 'WPLead'],
                    ['title' => 'Build pages (Home/About/Services/Contact)', 'estimate_minutes' => 480, 'role_hint' => 'WPLead'],
                    ['title' => 'SEO basics + sitemap + analytics', 'estimate_minutes' => 120, 'role_hint' => 'WPLead'],
                    ['title' => 'Speed optimization (cache/image)', 'estimate_minutes' => 180, 'role_hint' => 'WPLead'],
                    ['title' => 'Security hardening + backups', 'estimate_minutes' => 120, 'role_hint' => 'WPLead'],
                    ['title' => 'Cross-device QA + fixes', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'Deploy + client training', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                ],
            ],
            [
                'code' => 'laravel',
                'name' => 'Laravel App Template',
                'sort_order' => 20,
                'items' => [
                    ['title' => 'Repo setup + env + baseline config', 'estimate_minutes' => 120, 'role_hint' => 'LaravelLead'],
                    ['title' => 'DB design review + migrations', 'estimate_minutes' => 240, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Auth/roles/permissions setup', 'estimate_minutes' => 180, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Core CRUD modules implementation', 'estimate_minutes' => 600, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Integration (mail/sms/payment) if needed', 'estimate_minutes' => 240, 'role_hint' => 'LaravelLead'],
                    ['title' => 'QA + edge case fixes', 'estimate_minutes' => 240, 'role_hint' => 'Owner'],
                    ['title' => 'Deploy + queue/scheduler setup', 'estimate_minutes' => 180, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Handover + ops checklist', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                ],
            ],
            [
                'code' => 'pos',
                'name' => 'POS/ERP Delivery Template',
                'sort_order' => 30,
                'items' => [
                    ['title' => 'Requirements + workflow mapping', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                    ['title' => 'Install + baseline configuration', 'estimate_minutes' => 180, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Products/customers import setup', 'estimate_minutes' => 180, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Tax/discount/receipt settings', 'estimate_minutes' => 180, 'role_hint' => 'LaravelLead'],
                    ['title' => 'Roles & access rules', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                    ['title' => 'Training session', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                    ['title' => 'QA + go-live checklist', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'Deploy + backup plan', 'estimate_minutes' => 120, 'role_hint' => 'LaravelLead'],
                ],
            ],
            [
                'code' => 'hosting',
                'name' => 'Hosting/Server Setup Template',
                'sort_order' => 40,
                'items' => [
                    ['title' => 'Provision server/VPS & OS baseline', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'DNS setup & propagation checks', 'estimate_minutes' => 90, 'role_hint' => 'Owner'],
                    ['title' => 'Web server + PHP + DB install/config', 'estimate_minutes' => 240, 'role_hint' => 'Owner'],
                    ['title' => 'SSL install + auto-renew', 'estimate_minutes' => 90, 'role_hint' => 'Owner'],
                    ['title' => 'Email setup (optional)', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                    ['title' => 'Firewall/security hardening', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'Monitoring + log rotation', 'estimate_minutes' => 120, 'role_hint' => 'Owner'],
                    ['title' => 'Backups (daily/weekly) setup', 'estimate_minutes' => 180, 'role_hint' => 'Owner'],
                    ['title' => 'Handover credentials + documentation', 'estimate_minutes' => 90, 'role_hint' => 'Owner'],
                ],
            ],
        ];

        foreach ($templates as $tpl) {
            $template = TaskTemplate::withTrashed()->firstOrNew(['code' => $tpl['code']]);
            $template->fill([
                'name' => $tpl['name'],
                'description' => $tpl['description'] ?? null,
                'is_active' => true,
                'sort_order' => $tpl['sort_order'] ?? 0,
            ]);
            if ($template->trashed()) {
                $template->restore();
            }
            $template->save();

            $keepTitles = [];
            foreach ($tpl['items'] as $i => $item) {
                $title = $item['title'];
                $keepTitles[] = $title;

                $row = TaskTemplateItem::withTrashed()->firstOrNew([
                    'task_template_id' => $template->id,
                    'title' => $title,
                ]);

                $row->fill([
                    'description' => $item['description'] ?? null,
                    'default_status' => $item['default_status'] ?? 'backlog',
                    'sort_order' => $item['sort_order'] ?? (($i + 1) * 10),
                    'estimate_minutes' => $item['estimate_minutes'] ?? null,
                    'role_hint' => $item['role_hint'] ?? null,
                ]);

                if ($row->trashed()) {
                    $row->restore();
                }

                $row->save();
            }

            // remove old items not in template anymore (soft delete)
            TaskTemplateItem::query()
                ->where('task_template_id', $template->id)
                ->whereNotIn('title', $keepTitles)
                ->delete();
        }
    }
}
