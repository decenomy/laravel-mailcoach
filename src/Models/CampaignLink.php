<?php

namespace Spatie\Mailcoach\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignLink extends Model
{
    public $table = 'mailcoach_campaign_links';

    public $casts = [
        'click_count' => 'integer',
        'unique_click_count' => 'integer',
    ];

    protected $guarded = [];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(config('mailcoach.models.campaign'), 'campaign_id');
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(CampaignClick::class);
    }

    public function registerClick(Send $send, ?DateTimeInterface $clickedAt = null): CampaignClick
    {
        /** @var \Spatie\Mailcoach\Models\CampaignClick $campaignClick */
        $campaignClick = $this->clicks()->create([
            'send_id' => $send->id,
            'subscriber_id' => $send->subscriber->id,
            'created_at' => $clickedAt ?? now(),
        ]);

        $numberOfTimesClickedBySubscriber = $this->clicks()
            ->where('subscriber_id', $send->subscriber->id)
            ->count();

        if ($numberOfTimesClickedBySubscriber === 1) {
            $this->increment('unique_click_count');
        }

        $this->increment('click_count');

        return $campaignClick;
    }
}
