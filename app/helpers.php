<?php

/** returns a valid phone number that is suitable to be saved in database
 * @param string $mobile
 * @return string
 */
function to_valid_mobile_number(string $mobile)
{
    return $mobile = '+98' . substr($mobile, -10, 10);
}
