{{-- <div>
    Care about people's approval and you will be their prisoner.
    <div class="toggle-container">
        @foreach($states as $state)
            <button 
                wire:click="setState('{{ $state }}')"
                class="toggle-btn {{ $activeState === $state ? 'active' : '' }}"
            >
                {{ ucfirst($state) }}
            </button>
        @endforeach
    </div>

    <div class="content-section">
        {{ $content[$activeState] ?? 'No content' }}
    </div>
</div>

<style>
.toggle-container {
    display: flex;
    gap: 5px;
    background: #f0f0f0;
    border-radius: 25px;
    padding: 5px;
}

.toggle-btn {
    flex: 1;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-btn.active {
    background: #3b82f6;
    color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.content-section {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #eee;
    border-radius: 8px;
}
</style> --}}


    {{-- <div class="toggle-container">
        <div class="toggle-wrapper">
            <button class="toggle-btn @if($activeState === 'low') active @endif" 
                    wire:click="setState('low')">Low</button>
            <button class="toggle-btn @if($activeState === 'medium') active @endif" 
                    wire:click="setState('medium')">Medium</button>
            <button class="toggle-btn @if($activeState === 'high') active @endif" 
                    wire:click="setState('high')">High</button>
        </div>
    </div>
<style>
.toggle-wrapper {
    display: flex;
    width: 300px;
    background: #f0f0f0;
    border-radius: 25px;
    padding: 5px;
}

.toggle-btn {
    flex: 1;
    padding: 12px 20px;
    border: none;
    background: none;
    cursor: pointer;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.toggle-btn.active {
    background: #4CAF50;
    color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.item-list {
    margin-top: 20px;
    opacity: 0;
    animation: fadeIn 0.3s forwards;
}

@keyframes fadeIn {
    to { opacity: 1; }
}
</style> --}}
<div>
    <style>
        .triple-toggle-button {
            /* position: relative;
            display: inline-flex;
            height: 32px;
            width: 96px;
            align-items: center;
            border-radius: 9999px;
            background-color: #e5e7eb;
            transition: background-color 0.2s; */
    align-items: center;
    position: relative;
    display: inline-block;
    padding: 2px;
    width: 338px;
    height: 40px;
    border-radius: 22px;
    background-color: #43e6b1;
    box-shadow: inset 0px 0px 20px 0px #373737;
    cursor: pointer;
        }

        .toggle-thumb {
            position: absolute;
            display: inline-block;
            /* height: 24px;
            width: 24px;
            border-radius: 50%;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s; */
    /* position: absolute; */
    width: 168px;
    height: 40px;
    border-radius: 20px;
    background: linear-gradient(
        150deg,
        #dbb961 16.65%,
        #f2603e 50.78%,
        #b92136 84.58%
    );
    transition: transform 0.3s;
        }

        .status-content {
            margin-top: 16px;
            font-size: 1.25rem;
            line-height: 1.75rem;
            transition: all 0.3s;
        }

        .text-red { color: #ef4444; }
        .text-yellow { color: #eab308; }
        .text-green { color: #22c55e; }
    </style>

    <!-- Triple Toggle -->
    <button 
        type="button"
        wire:click="cycleStatus"
        class="triple-toggle-button"
        
    >
        <span 
            class="toggle-thumb"
            {{-- style="left: {{ match($status) {
                'low' => '4px',
                'medium' => '32px',
                'high' => '64px',
                default: '4px'
            } }}" --}}
        ></span>
    </button>

    <!-- Content -->
</div>