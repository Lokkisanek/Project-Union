<div class="relative w-full overflow-hidden">
    {{-- Background color stretched across page (heroColor) to mimic Steam-style color fill --}}
    @if(!empty($heroProject) && $heroProject->main_image)
        {{-- full-bleed wrapper with blurred background underneath --}}
        <div class="w-full relative overflow-hidden py-8" style="position:relative;">
            <div class="absolute inset-0 -z-10 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $heroProject->main_image) }}'); filter: blur(24px) saturate(0.8) brightness(0.6); transform: scale(1.05);"></div>

            {{-- Centered row inside normal flow so wrapper has height (py-8) --}}
            <div class="w-full max-w-7xl mx-auto px-4">
                <div class="flex items-stretch">
                    {{-- left side fill --}}
                    <div class="flex-1" style="background-color: {{ $heroColor ?? '#071028' }};"></div>

                    {{-- center image container (16:9) --}}
                    <div class="flex-shrink-0" style="width:100%; max-width:1200px;">
                        <div style="position:relative; width:100%; padding-top:56.25%; overflow:hidden; border-radius:8px;">
                            <a href="{{ route('projects.show', $heroProject) }}" class="absolute inset-0 block">
                                <img src="{{ asset('storage/' . $heroProject->main_image) }}" alt="{{ $heroProject->title }}" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; display:block;">
                            </a>
                            {{-- left/right fade overlays to blend image into side color --}}
                            <div style="position:absolute; left:0; top:0; bottom:0; width:12%; pointer-events:none; background: linear-gradient(to left, rgba(0,0,0,0), {{ $heroColor ?? '#071028' }});"></div>
                            <div style="position:absolute; right:0; top:0; bottom:0; width:12%; pointer-events:none; background: linear-gradient(to right, rgba(0,0,0,0), {{ $heroColor ?? '#071028' }});"></div>
                        </div>
                    </div>

                    {{-- right side fill --}}
                    <div class="flex-1" style="background-color: {{ $heroColor ?? '#071028' }};"></div>
                </div>
            </div>
        </div>
    @else
        <div class="w-full" style="background: linear-gradient(90deg,#071028 0%,#0b1b2b 100%);">
            <div class="relative">
                <div style="height:360px;" class="w-full"></div>
            </div>
        </div>
    @endif
</div>
