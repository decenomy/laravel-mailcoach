<?php

namespace Spatie\Mailcoach\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    public $table = 'mailcoach_tags';

    public $guarded = [];

    public function subscribers()
    {
        return $this->belongsToMany(config('mailcoach.models.subscriber'), 'mailcoach_email_list_subscriber_tags', 'tag_id', 'subscriber_id');
    }

    public function emailList(): BelongsTo
    {
        return $this->belongsTo(config('mailcoach.models.email_list'), 'email_list_id');
    }

    public function scopeEmailList(Builder $query, EmailList $emailList): void
    {
        $query->where('email_list_id', $emailList->id);
    }
}
