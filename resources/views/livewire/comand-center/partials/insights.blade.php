{{-- INSIGHTS AI --}}
@if(!empty($insights))
<div class="glassmorphism rounded-2xl p-8 border border-slate-700/50" data-aos="fade-up">
    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
        <span class="text-2xl">🤖</span>
        <span class="bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent">AI Insights</span>
    </h3>
    <div class="space-y-3">
        @foreach($insights as $insight)
            <div class="p-4 rounded-xl bg-gradient-to-r from-{{ $insight['type'] === 'success' ? 'emerald' : ($insight['type'] === 'warning' ? 'yellow' : 'red') }}-500/10 to-transparent border border-{{ $insight['type'] === 'success' ? 'emerald' : ($insight['type'] === 'warning' ? 'yellow' : 'red') }}-500/30">
                <p class="text-sm text-slate-200 flex items-center gap-2">
                    <span class="text-xl">{{ $insight['icon'] }}</span>
                    <span>{{ $insight['text'] }}</span>
                </p>
            </div>
        @endforeach
    </div>
</div>
@endif
