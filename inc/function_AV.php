<?php

function avarage_value($kenteken,$kmstand)
{
    if ($kenteken != 'Onbekend') {
        $kenteken = str_replace('-', '', $kenteken);
        return "http://www.marktplaats.nl/m/auto/auto-verkopen/#/".$kenteken."/".$kmstand;
    }
}
