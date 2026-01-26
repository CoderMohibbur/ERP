<?php

namespace App\Support;

use InvalidArgumentException;

final class Enums
{
    // LeadStatus: new, contacted, qualified, unqualified
    public const LEAD_STATUS_NEW         = 'new';
    public const LEAD_STATUS_CONTACTED   = 'contacted';
    public const LEAD_STATUS_QUALIFIED   = 'qualified';
    public const LEAD_STATUS_UNQUALIFIED = 'unqualified';

    // DealStage: new, contacted, quoted, negotiating, won, lost
    public const DEAL_STAGE_NEW         = 'new';
    public const DEAL_STAGE_CONTACTED   = 'contacted';
    public const DEAL_STAGE_QUOTED      = 'quoted';
    public const DEAL_STAGE_NEGOTIATING = 'negotiating';
    public const DEAL_STAGE_WON         = 'won';
    public const DEAL_STAGE_LOST        = 'lost';

    // TaskStatus: backlog, doing, review, done, blocked
    public const TASK_STATUS_BACKLOG = 'backlog';
    public const TASK_STATUS_DOING   = 'doing';
    public const TASK_STATUS_REVIEW  = 'review';
    public const TASK_STATUS_DONE    = 'done';
    public const TASK_STATUS_BLOCKED = 'blocked';

    // InvoiceStatus: draft, unpaid, partial, paid, void
    public const INVOICE_STATUS_DRAFT   = 'draft';
    public const INVOICE_STATUS_UNPAID  = 'unpaid';
    public const INVOICE_STATUS_PARTIAL = 'partial';
    public const INVOICE_STATUS_PAID    = 'paid';
    public const INVOICE_STATUS_VOID    = 'void';

    // ServiceStatus: active, suspended, cancelled, expired
    public const SERVICE_STATUS_ACTIVE    = 'active';
    public const SERVICE_STATUS_SUSPENDED = 'suspended';
    public const SERVICE_STATUS_CANCELLED = 'cancelled';
    public const SERVICE_STATUS_EXPIRED   = 'expired';

    // RenewalStatus: pending, invoiced, paid, skipped
    public const RENEWAL_STATUS_PENDING = 'pending';
    public const RENEWAL_STATUS_INVOICED = 'invoiced';
    public const RENEWAL_STATUS_PAID    = 'paid';
    public const RENEWAL_STATUS_SKIPPED = 'skipped';

    // ActivityType: call, whatsapp, email, meeting, note
    public const ACTIVITY_TYPE_CALL     = 'call';
    public const ACTIVITY_TYPE_WHATSAPP = 'whatsapp';
    public const ACTIVITY_TYPE_EMAIL    = 'email';
    public const ACTIVITY_TYPE_MEETING  = 'meeting';
    public const ACTIVITY_TYPE_NOTE     = 'note';

    // ActivityStatus (spec mentions open/done for activities)
    public const ACTIVITY_STATUS_OPEN = 'open';
    public const ACTIVITY_STATUS_DONE = 'done';

    /** @return string[] */
    public static function leadStatuses(): array
    {
        return [
            self::LEAD_STATUS_NEW,
            self::LEAD_STATUS_CONTACTED,
            self::LEAD_STATUS_QUALIFIED,
            self::LEAD_STATUS_UNQUALIFIED,
        ];
    }

    /** @return string[] */
    public static function dealStages(): array
    {
        return [
            self::DEAL_STAGE_NEW,
            self::DEAL_STAGE_CONTACTED,
            self::DEAL_STAGE_QUOTED,
            self::DEAL_STAGE_NEGOTIATING,
            self::DEAL_STAGE_WON,
            self::DEAL_STAGE_LOST,
        ];
    }

    /** @return string[] */
    public static function taskStatuses(): array
    {
        return [
            self::TASK_STATUS_BACKLOG,
            self::TASK_STATUS_DOING,
            self::TASK_STATUS_REVIEW,
            self::TASK_STATUS_DONE,
            self::TASK_STATUS_BLOCKED,
        ];
    }

    /** @return string[] */
    public static function invoiceStatuses(): array
    {
        return [
            self::INVOICE_STATUS_DRAFT,
            self::INVOICE_STATUS_UNPAID,
            self::INVOICE_STATUS_PARTIAL,
            self::INVOICE_STATUS_PAID,
            self::INVOICE_STATUS_VOID,
        ];
    }

    /** @return string[] */
    public static function serviceStatuses(): array
    {
        return [
            self::SERVICE_STATUS_ACTIVE,
            self::SERVICE_STATUS_SUSPENDED,
            self::SERVICE_STATUS_CANCELLED,
            self::SERVICE_STATUS_EXPIRED,
        ];
    }

    /** @return string[] */
    public static function renewalStatuses(): array
    {
        return [
            self::RENEWAL_STATUS_PENDING,
            self::RENEWAL_STATUS_INVOICED,
            self::RENEWAL_STATUS_PAID,
            self::RENEWAL_STATUS_SKIPPED,
        ];
    }

    /** @return string[] */
    public static function activityTypes(): array
    {
        return [
            self::ACTIVITY_TYPE_CALL,
            self::ACTIVITY_TYPE_WHATSAPP,
            self::ACTIVITY_TYPE_EMAIL,
            self::ACTIVITY_TYPE_MEETING,
            self::ACTIVITY_TYPE_NOTE,
        ];
    }

    /** @return string[] */
    public static function activityStatuses(): array
    {
        return [
            self::ACTIVITY_STATUS_OPEN,
            self::ACTIVITY_STATUS_DONE,
        ];
    }

    /**
     * Generic guard helper for Requests/Services
     */
    public static function assertIn(string $value, array $allowed, string $label = 'value'): string
    {
        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException("Invalid {$label}: {$value}");
        }
        return $value;
    }

    /**
     * Handy for seeders / UI dropdowns
     * @return array<string, string[]>
     */
    public static function all(): array
    {
        return [
            'lead_statuses'    => self::leadStatuses(),
            'deal_stages'      => self::dealStages(),
            'task_statuses'    => self::taskStatuses(),
            'invoice_statuses' => self::invoiceStatuses(),
            'service_statuses' => self::serviceStatuses(),
            'renewal_statuses' => self::renewalStatuses(),
            'activity_types'   => self::activityTypes(),
            'activity_statuses'=> self::activityStatuses(),
        ];
    }
}
