@php
$visit = New \Shetabit\Visitor\Models\Visit;
$visit_count = 0;
foreach($visit->select('ip', 'useragent')->groupBy('ip', 'useragent')->get() as $v)
{
    $visit_count ++;
}

@endphp
This page has been viewed {{$visit_count }} times 