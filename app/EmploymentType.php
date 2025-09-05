<?php

namespace App;

enum EmploymentType: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
    case INTERN = 'intern';
    case FREELANCE = 'freelance';
}
