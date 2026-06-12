<?php

namespace Tests\Unit;

use App\Models\FieldTrip;
use App\Models\Payment;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StatusConstantsTest extends TestCase
{
    #[Test]
    public function field_trip_has_correct_status_constants(): void
    {
        $this->assertSame('open', FieldTrip::STATUS_OPEN);
        $this->assertSame('completed', FieldTrip::STATUS_COMPLETED);
        $this->assertSame('cancelled', FieldTrip::STATUS_CANCELLED);
        $this->assertSame(['open', 'completed', 'cancelled'], FieldTrip::STATUSES);
    }

    #[Test]
    public function field_trip_statuses_array_matches_constants(): void
    {
        $this->assertContains(FieldTrip::STATUS_OPEN, FieldTrip::STATUSES);
        $this->assertContains(FieldTrip::STATUS_COMPLETED, FieldTrip::STATUSES);
        $this->assertContains(FieldTrip::STATUS_CANCELLED, FieldTrip::STATUSES);
    }

    #[Test]
    public function payment_has_correct_status_constants(): void
    {
        $this->assertSame('pending', Payment::STATUS_PENDING);
        $this->assertSame('paid', Payment::STATUS_PAID);
        $this->assertSame('refunded', Payment::STATUS_REFUNDED);
    }
}
