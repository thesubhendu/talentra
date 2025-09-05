<?php

namespace App;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case REVIEWING = 'reviewing';
    case INTERVIEWING = 'interviewing';
    case OFFER_SENT = 'offer_sent';
    case HIRED = 'hired';
    case REJECTED = 'rejected';
}
