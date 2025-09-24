@props(['data'])

@php
    $isAffiliate = isset($data['linkType']) && $data['linkType'] === 'affiliate';
    $isReview = isset($data['linkType']) && $data['linkType'] === 'review';
    $isSource = isset($data['source']) && $data['source'] === 'knowledge_base';
@endphp

@if($isAffiliate)
    <x-chat.affiliate-card :data="$data" />
@elseif($isReview || $isSource)
    <x-chat.source-card :data="$data" />
@else
    <x-chat.json-fallback :data="$data" />
@endif
