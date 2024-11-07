<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ins_comercial_name',
        'ins_address',
        'ins_hood',
        'ins_cp',
        'ins_email',
        'com_capacity',
        'com_male',
        'com_female',
        'com_disabled',
        'com_open_date',
        'com_license_status',
        'com_license_type',
        'tax_name',
        'tax_rfc',
        'tax_street',
        'tax_hood',
        'tax_cp',
        'tax_locality',
        'tax_payment',
        'con_name',
        'con_role',
        'con_phone',
        'con_email',
        'com_hours',
        'com_line',
        'com_desc',
        'sm_facebook',
        'sm_instagram',
        'sm_twitter',
        'sm_email',
        'sm_phone',
        'sm_web',
        'sm_other',
        'sv_have_wifi',
        'sv_have_ac',
        'sv_have_live_music',
        'sv_have_deck',
        'sv_have_lounge',
        'sv_lounge_capacity',
        'sv_other',
        'status',
    ];
}
