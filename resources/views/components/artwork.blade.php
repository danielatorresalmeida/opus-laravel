@props(['path' => null, 'kind' => 'composer'])
<img {{ $attributes->except('alt')->class(['artwork', 'score' => $kind === 'score']) }} src="{{ $path ? \Illuminate\Support\Facades\Storage::disk('public')->url($path) : asset('images/' . ($kind === 'score' ? 'score-placeholder.svg' : 'composer-placeholder.svg')) }}" alt="{{ $attributes->get('alt', '') }}" loading="lazy">
